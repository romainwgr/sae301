<?php
class Controller_dashboard extends Controller {

    public function action_dashboard() {
        $this->render('dashboard');
    }

    
    public function action_chart() {
        $m = Model::getModel();
        $data = $m->getChartData();

        echo json_encode($data);

    }

    public function action_chartCategory() {
        $m = Model::getModel();
        $data = $m->getChartCategory();

        echo json_encode($data);

    }
 
    public function action_chartCategoryByDepartement() {
        $m = Model::getModel();

        $data = $m->chartCategoryByDepartement();

        echo json_encode($data);
    }

    public function action_chartHoursbyDiscipline() {
        $m = Model::getModel();

        $data = $m->chartHoursByDiscipline();

        echo json_encode($data);
    }

    public function action_default() {
        $this->render('dashboard');
    }
}
