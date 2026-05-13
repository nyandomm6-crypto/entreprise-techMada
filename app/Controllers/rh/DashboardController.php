<?php

namespace App\Controllers\front;

use App\Controllers\BaseController;
use App\Models\DemandeModel;
use App\Models\StatutModel;
use App\Models\EmployesModel ;

class DashboardController extends BaseController
{
	private StatutModel $statutModel;
	private DemandeModel $demandeModel;
    private EmployesModel $Model;

	public function __construct()
	{
		$this->statutModel = new StatutModel();
		$this->demandeModel = new DemandeModel();
	}

	public function index()
	{
		$utilisateurId = $this->getUtilisateurId();

		if ($utilisateurId === null) {
			return redirect()->to('/');
		}
		$data = $this->buildDashboardData($utilisateurId);

		return view('front/dashboard/index', $data);
	}

}