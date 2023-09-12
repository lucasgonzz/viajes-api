<?php

namespace App\Http\Controllers\Helpers\Pdf;

use App\Http\Controllers\Helpers\ImageHelper;
use fpdf;
require(__DIR__.'/../../fpdf/fpdf.php');

class BasePdf extends fpdf {

	function __construct($model) {
		parent::__construct();
		$this->SetAutoPageBreak(true, 1);
		$this->b = 0;
		$this->line_height = 7;

		$this->model = $model;
		$this->user = Auth()->user();
		
		$this->AddPage();

		// $this->print();

        $this->Output();
        exit;
	}

	function Header() {
		$this->logo();
		// $this->modelNum();
		// $this->commerceInfo();
		$this->clientInfo();
	}

	function logo() {
        // Logo
        if (!is_null($this->user->image_url)) {
        	$this->Image(ImageHelper::image($this->user), 5, 5, 0, 27);
        }
		
        // Company name
		$this->SetFont('Arial', 'B', 10);
		$this->x = 5;
		$this->y = 30;
		$this->Cell(100, 5, $this->user->company_name, $this->b, 0, 'C');
	}

	function modelNum() {
		$this->SetFont('Arial', 'B', 14);
		$this->x = 105;
		$this->y = 5;

		// Numero
		$this->Cell(100, 10, 'N° '.$this->budget->num, $this->b, 0, 'L');
		$this->y += 10;
		$this->x = 105;
		$this->Cell(100, 10, date_format($this->budget->created_at, 'd/m/Y'), $this->b, 0, 'L');
	}

	function clientInfo() {
		$this->SetFont('Arial', '', 10);
		$this->x = 105;
		$this->y = 58;

		$this->Cell(100, 5, 'Nombre: '.$this->model->client->name, $this->b, 0, 'L');
		$this->Cell(100, 5, 'Direccion: '.$this->model->client->address, $this->b, 0, 'L');
	}

}