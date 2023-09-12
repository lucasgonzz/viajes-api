<?php

namespace App\Http\Controllers\Helpers\Pdf;

use App\Http\Controllers\Helpers\ImageHelper;
use fpdf;
require(__DIR__.'/../../fpdf/fpdf.php');

class OrderPdf extends fpdf {

	function __construct($model) {
		parent::__construct();
		$this->SetAutoPageBreak(true, 1);
		$this->b = 0;
		$this->line_height = 7;

		$this->model = $model;
		$this->user = Auth()->user();
		$this->initWidths();
		$this->total = 0;
		
		$this->AddPage();


		// $this->print();

        $this->Output();
        exit;
	}

	function initWidths() {
		$this->widths = [
			'concepto' 	=> 60,
			'cantidad' 	=> 40,
			'precio' 	=> 40,
			'sub_total' => 60,
		];
	}

	function Header() {
		$this->logo();
		$this->modelNum();
		
		// Conductor -  localidad y tipo de pedido
		$this->orderInfo();
		$this->clientInfo();
		$this->addressInfo();
		$this->items();
	}

	function logo() {
        // Logo
        // if (!is_null($this->user->image_url)) {
        // 	$this->Image(ImageHelper::image($this->user), 5, 5, 0, 27);
        // }
		
        // Company name
		$this->SetFont('Arial', 'B', 16);
		$this->x = 5;
		$this->y = 10;
		$this->Cell(100, 5, $this->user->company_name, $this->b, 0, 'L');
	}

	function modelNum() {
		$this->SetFont('Arial', 'B', 14);
		$this->x = 105;
		$this->y = 5;

		// Numero
		$this->Cell(50, 10, 'N° '.$this->model->num, $this->b, 0, 'L');
		$this->Cell(50, 10, date_format($this->model->created_at, 'd/m/Y'), $this->b, 0, 'R');
	}

	function orderInfo() {
		$this->SetFont('Arial', '', 12);
		$this->x = 5;
		$this->y = 20;

		if (!is_null($this->model->driver)) {
			$this->Cell(100, 7, 'Conductor: '.$this->model->driver->name, $this->b, 0, 'L');
		}
		
		if (!is_null($this->model->location)) {
			$this->y += 7;
			$this->x = 5;
			$this->Cell(100, 7, 'Localidad: '.$this->model->location->name, $this->b, 0, 'L');
		}
		
		if (!is_null($this->model->order_type)) {
			$this->y += 7;
			$this->x = 5;
			$this->Cell(100, 7, 'Tipo pedido: '.$this->model->order_type->name, $this->b, 0, 'L');
		}
	}

	function clientInfo() {
		$this->SetFont('Arial', '', 12);
		$this->x = 105;
		$this->y = 15;

		if (!is_null($this->model->client)) {
			$this->Cell(100, 7, 'Cliente: '.$this->model->client->name, $this->b, 0, 'L');
			if (!is_null($this->model->client->address)) {
				$this->y += 7;
				$this->x = 105;
				$this->Cell(100, 7, 'Direccion: '.$this->model->client->address, $this->b, 0, 'L');
			} 
		}
	}

	function addressInfo() {
		$this->SetFont('Arial', '', 12);

		if (!is_null($this->model->origin_client_address)) {
			$this->x = 105;
			$this->y = 30;
			$this->Cell(100, 7, 'Direccion origen: '.$this->model->origin_client_address->address, $this->b, 0, 'L');
		}
		if (!is_null($this->model->destination_client_address)) {
			$this->x = 105;
			$this->y += 7;
			$this->Cell(100, 7, 'Direccion destino: '.$this->model->destination_client_address->address, $this->b, 0, 'L');
		}
	}

	function items() {
		if (!is_null($this->model->order_type)) {
			$this->tableHeader();
			if ($this->model->order_type->name == 'Bultos') {
				$this->printPackages();
			} else if ($this->model->order_type->name == 'Pasajeros') {
				$this->printPasajeros();
			} else if ($this->model->order_type->name == 'Plata') {
				$this->printPlata();
			}
			$this->total();
		}
	}

	function tableHeader() {
		$this->x = 5;
		$this->y = 55;
		$this->Cell($this->widths['concepto'], 10, 'Concepto', 1, 0, 'C');
		$this->Cell($this->widths['cantidad'], 10, 'Cantidad', 1, 0, 'C');
		$this->Cell($this->widths['precio'], 10, 'Precio', 1, 0, 'C');
		$this->Cell($this->widths['sub_total'], 10, 'Sub Total', 1, 0, 'C');
		$this->y += 10;
		$this->x = 5;
	}

	function printPackages() {
		foreach ($this->model->packages as $package) {
			$total = $package->pivot->price * $package->pivot->amount;
			$this->Cell($this->widths['concepto'], 10, $package->name, 'B', 0, 'C');
			$this->Cell($this->widths['cantidad'], 10, $package->pivot->amount, 'B', 0, 'C');
			$this->Cell($this->widths['precio'], 10, '$'.$package->pivot->price, 'B', 0, 'C');
			$this->Cell($this->widths['sub_total'], 10, '$'.$total, 'B', 0, 'C');
			$this->total += $total;
			$this->x = 5;
			$this->y += 10;
		}
	}

	function printPasajeros() {
		$this->Cell($this->widths['concepto'], 10, 'Pasajeros', 'B', 0, 'C');
		$this->Cell($this->widths['cantidad'], 10, $this->model->person_amount, 'B', 0, 'C');
		$this->Cell($this->widths['precio'], 10, '$'.$this->model->person_price, 'B', 0, 'C');
		$this->Cell($this->widths['sub_total'], 10, '$'.$this->model->person_price * $this->model->person_amount, 'B', 0, 'C');
		$this->total += $this->model->person_price * $this->model->person_amount;
		$this->y += 10;
	}

	function printPlata() {
		$this->Cell($this->widths['concepto'], 10, 'Plata', 'B', 0, 'C');
		$this->Cell($this->widths['cantidad'], 10, '-', 'B', 0, 'C');
		$this->Cell($this->widths['precio'], 10, '$'.$this->model->money_price, 'B', 0, 'C');
		$this->Cell($this->widths['sub_total'], 10, '$'.$this->model->money_price, 'B', 0, 'C');
		$this->total = $this->model->money_price;
		$this->y += 10;
	}

	function total() {
		$this->SetFont('Arial', 'B', 14);
		$this->x = 105;
		$this->y += 5;
		$this->Cell(100, 10, 'Total: $'.$this->total, 0, 0, 'R');
	}

}