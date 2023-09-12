<?php

namespace App\Http\Controllers\Helpers\Pdf;

use App\Http\Controllers\Helpers\ImageHelper;
use fpdf;
require(__DIR__.'/../../fpdf/fpdf.php');

class CurrentAcountPdf extends fpdf {

	function __construct($models) {
		parent::__construct();
		$this->SetAutoPageBreak(true, 1);
		$this->b = 0;
		$this->line_height = 7;

		$this->models = $models;
		$this->user = Auth()->user();
		$this->initWidths();
		$this->total = 0;
		
		$this->AddPage();

		$this->printItems();

        $this->Output();
        exit;
	}

	function initWidths() {
		$this->widths = [
			'Fecha' 	=> 30,
			'Pedido N°' => 25,
			'Recibo N°' => 25,
			'Debe' 		=> 30,
			'Haber' 	=> 30,
			'Saldo' 	=> 30,
			'Met Pago' 	=> 30,
		];
	}

	function Header() {
		$this->logo();
		$this->_date();

		// Conductor -  localidad y tipo de pedido
		$this->clientInfo();
		$this->tableHeader();
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

	function _date() {
		$this->SetFont('Arial', 'B', 14);
		$this->x = 105;
		$this->y = 5;

		$this->Cell(50, 10, date('d/m/Y'), $this->b, 0, 'L');
	}

	function clientInfo() {
		$this->SetFont('Arial', '', 12);
		$this->x = 105;
		$this->y = 15;

		if (!is_null($this->models[0]->client)) {
			$this->Cell(100, 7, 'Cliente: '.$this->models[0]->client->name, $this->b, 0, 'L');
			if (!is_null($this->models[0]->client->address)) {
				$this->y += 7;
				$this->x = 105;
				$this->Cell(100, 7, 'Direccion: '.$this->models[0]->client->address, $this->b, 0, 'L');
			} 
		}
	}

	function tableHeader() {
		$this->x = 5;
		$this->y = 35;
		foreach ($this->widths as $key => $value) {
			$this->Cell($value, 10, $key, 1, 0, 'C');
		}
		$this->y += 10;
		$this->x = 5;
	}

	function printItems() {
		foreach ($this->models as $model) {
			if ($this->y >= 270) {
				$this->AddPage();
			} 
			$this->Cell($this->widths['Fecha'], 10, date_format($model->created_at, 'd/m/Y'), 'B', 0, 'C');
			$this->Cell($this->widths['Pedido N°'], 10, $this->orderNum($model), 'B', 0, 'C');
			$this->Cell($this->widths['Recibo N°'], 10, $model->num_receipt, 'B', 0, 'C');
			$this->Cell($this->widths['Debe'], 10, $this->debe($model), 'B', 0, 'C');
			$this->Cell($this->widths['Haber'], 10, $this->haber($model), 'B', 0, 'C');
			$this->Cell($this->widths['Saldo'], 10, '$'.$model->saldo, 'B', 0, 'C');
			$this->Cell($this->widths['Met Pago'], 10, $this->paymentMethod($model), 'B', 0, 'C');
			$this->x = 5;
			$this->y += 10;
		}
	}

	function orderNum($model) {
		if (!is_null($model->order_id)) {
			return $model->order->num;
		}
		return '';
	}

	function debe($model) {
		if (!is_null($model->debe)) {
			return '$'.$model->debe;
		}
		return '';
	}

	function haber($model) {
		if (!is_null($model->haber)) {
			return '$'.$model->haber;
		}
		return '';
	}

	function paymentMethod($model) {
		if ($model->current_acount_payment_method_id != 0) {
			return $model->current_acount_payment_method->name;
		}
		return '';
	}

	function Footer() {
		$this->SetY(290);
		$this->SetX(5);
		$this->Cell(200, 5, 'Comprobante emitido con Transporteagil.com', 0, 0, 'C');
	}
}