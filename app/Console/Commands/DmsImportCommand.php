<?php

namespace App\Console\Commands;

use App\Models\Caravan;
use App\Repositories\BranchRepository;
use App\Repositories\CaravanRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\TypeRepository;
use Illuminate\Console\Command;

/**
 * Class DmsImportCommand
 * @package App\Console\Commands
 */
class DmsImportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dms:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'DMS Data Importer';

    /**
     * @var string
     */
    private string $dmsUrl = 'https://services.dmservices.co.uk/DmsNavigator.NavigatorWebService.svc/GetNavigatorReport/194789.775.4087.48E7.EA2C_6B696D6265726C65792E646D73657276696365732E636F2E756B,1,xml_vehicle_stock_list,json,1,KAL,,1,1,1,,1';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param BranchRepository $branchRepo
     * @param CategoryRepository $categoryRepo
     * @param TypeRepository $typeRepo
     * @return int
     */
    public function handle(BranchRepository $branchRepo, CategoryRepository $categoryRepo, TypeRepository $typeRepo)
    {
        // load data from the url and decode the json
        $data = json_decode(file_get_contents($this->dmsUrl), JSON_OBJECT_AS_ARRAY);

        // get reports
        if (isset($data['x:reports']) && isset($data['x:reports']['report']) && !empty($data['x:reports']['report'])) {
            /** @var array $reports */
            $reports = $data['x:reports']['report'];

            // loop and do inserts
            foreach($reports as $report){

                // convert to object - nicer to work with
                $report = (object)$report;

                // get the branch, or create it
                $branch = $branchRepo->getByNameOrCreate($report->Branch);

                // get the category, or create it
                $category = $categoryRepo->getByNameOrCreate($report->Category);

                // getr the type, or create it
                $type = $typeRepo->getByNameOrCreate($report->Type);

                // create the caravan
                if((int)$report->Kimberley_Unit_Id !== 0) {

                    /** @var Caravan $caravan */
                    if(!$caravan = Caravan::where('kimberley_unit_id',(int)$report->Kimberley_Unit_Id)->first()){
                        // new object
                        $caravan = new Caravan;
                    }

                    // add data
                    $caravan->stock = (int)$report->STOCK;
                    $caravan->branch_id = $branch->id;
                    $caravan->category_id = $category->id;
                    $caravan->type_id = $type->id;
                    $caravan->reg = $report->Reg;
                    $caravan->make = $report->Make;
                    $caravan->model = $report->Model;
                    $caravan->specification = $report->Specification;
                    $caravan->derivative = $report->Derivative;
                    $caravan->engine_size = $report->Engine_Size;
                    $caravan->engine_type = $report->Engine_Type;
                    $caravan->transmission = $report->Transmission;
                    $caravan->colour = $report->Colour;
                    $caravan->year = (int)$report->Year;
                    $caravan->mileage = (int)$report->Mileage;
                    $caravan->commercial = (bool)$report->Commercial;
                    $caravan->sales_siv = (double)$report->Sales_SIV;
                    $caravan->retail = (double)$report->Retail;
                    $caravan->web_price = (double)$report->Web_Price;
                    $caravan->sub_heading = $report->Sub_Heading;
                    $caravan->advertising_notes = $report->Advertising_Notes;
                    $caravan->manager_comments = $report->Manager_Comments;
                    $caravan->previous_price = (double)$report->Previous_Price;
                    $caravan->guide_retail_price = (double)$report->Guide_Retail;
                    $caravan->available_for_sale = (bool)$report->Available_for_Sale;
                    $caravan->advertised_on_own_website = (bool)$report->Advertise_on_Own_Web_Site;
                    $caravan->berths = (int)$report->Berths;
                    $caravan->axles = (int)$report->Axles;
                    $caravan->layout_type = $report->Layout_Type_;
                    $caravan->width = (double)$report->Width;
                    $caravan->length = (double)$report->Length;
                    $caravan->height = (double)$report->Height;
                    $caravan->kimberley_unit_id = (int)$report->Kimberley_Unit_Id;
                    $caravan->kimberley_date_updated = \DateTime::createFromFormat('d/m/y',$report->Date_Record_Updated);

                    $caravan->save();

                }
            }
        }

        return 0;
    }
}
