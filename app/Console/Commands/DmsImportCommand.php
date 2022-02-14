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
                    $caravan->setStock((int)$report->STOCK)
                        ->setBranchId($branch->id)
                        ->setCategory($category)
                        ->setType($type)
                        ->setBranch($branch)
                        ->setReg($report->Reg)
                        ->setMake($report->Make)
                        ->setModel($report->Model)
                        ->setSpecification($report->Specification)
                        ->setDerivative($report->Derivative)
                        ->setEngineSize($report->Engine_Size)
                        ->setEngineType($report->Engine_Type)
                        ->setTransmission($report->Transmission)
                        ->setColour($report->Colour)
                        ->setYear((int)$report->Year)
                        ->setMileage((int)$report->Mileage)
                        ->setCommercial((bool)$report->Commercial)
                        ->setSalesSiv((double)$report->Sales_SIV)
                        ->setRetail((double)$report->Retail)
                        ->setWebPrice((double)$report->Web_Price)
                        ->setSubHeading($report->Sub_Heading)
                        ->setAdvertisingNotes($report->Advertising_Notes)
                        ->setManagerComments($report->Manager_Comments)
                        ->setPreviousPrice((double)$report->Previous_Price)
                        ->setGuideRetailPrice((double)$report->Guide_Retail)
                        ->setAvailableForSale((bool)$report->Available_for_Sale)
                        ->setAdvertisedOnOwnWebsite((bool)$report->Advertise_on_Own_Web_Site)
                        ->setBerths((int)$report->Berths)
                        ->setAxles((int)$report->Axles)
                        ->setLayoutType($report->Layout_Type_)
                        ->setWidth((double)$report->Width)
                        ->setLength((double)$report->Length)
                        ->setHeight((double)$report->Height)
                        ->setKimberleyUnitId((int)$report->Kimberley_Unit_Id)
                        ->setKimberleyDateUpdated(\DateTime::createFromFormat('d/m/y',$report->Date_Record_Updated));

                    $caravan->save();

                }
            }
        }

        return 0;
    }
}
