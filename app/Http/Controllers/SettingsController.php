<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\ApiKeysForm;
use App\Http\Controllers\Controller;
use App\ApiKeys;
use Auth;
use Pheal\Pheal;

class SettingsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

        $this->middleware('auth');
    }
    public function index(){
        return view('settings',[
            'apikeys' => Auth::user()->apiKeys->toArray()
        ]);
    }
    public function addApiKey(ApiKeysForm $request) {
        $key = $request->input('key');
        $vCode = $request->input('vCode');
        $chars = $request->input('chars');
        $api = new ApiKeys();
        $api->key = $key;
        $api->vcode = $vCode;
        $api->user_id = Auth::user()->id;
        $api->characters = json_encode($chars);
        $api->access = json_encode($this->checkAccess($key,$vCode));
        $api->save();

        return redirect('/settings')->with('status', 'Api key added!');
    }
    public function getChars(ApiKeysForm $request) {
        $key = $request->input('key');
        $vCode = $request->input('vCode');

        $pheal = new Pheal($key,$vCode,'account');
        $characters = $pheal->Characters()->characters;
        return json_encode($characters);
    }
    public function setAccess($key){
    }


    /**
     * @param $key
     * @return bool
     * TODO Move to ApiController
     */

    private function checkAccess($key,$vCode){
        $pheal = new Pheal($key,$vCode,'account');
        try{
            $response = $pheal->APIKeyInfo();
            $accessMask = $response->key->accessMask;
        } catch (\Pheal\Exceptions\PhealException $e) {
            Log::error(get_class($e) .' |||| '. $e->getMessage());
            return false;
        }
        $rights =[];
        foreach($this->bits['char'] as $name => $value) {
            $enabled = 0;
            if($value[1] & $accessMask) {
                $enabled = 1;
            }
            $rights += [
                $name => $enabled
            ];
        }
        return $rights;
    }

    protected $bits = array(
        'char' => array(
            'contracts' => array('Character', 67108864),
            'wallettransactions' => array('Character', 4194304),
            'walletjournal' => array('Character', 2097152),
            'upcomingcalendarevents' => array('Character', 1048576),
            'standings' => array('Character', 524288),
            'skillqueue' => array('Character', 262144),
            'skillintraining' => array('Character', 131072),
            'research' => array('Character', 65536),
            'notificationtexts' => array('Character', 32768),
            'notifications' => array('Character', 16384),
            'medals' => array('Character', 8192),
            'marketorders' => array('Character', 4096),
            'mailmessages' => array('Character', 2048),
            'mailinglists' => array('Character', 1024),
            'mailbodies' => array('Character', 512),
            'killlog' => array('Character', 256),
            'industryjobs' => array('Character', 128),
            'facwarstats' => array('Character', 64),
            'contactnotifications' => array('Character', 32),
            'contactlist' => array('Character', 16),
            'charactersheet' => array('Character', 8),
            'calendareventattendees' => array('Character', 4),
            'assetlist' => array('Character', 2),
            'accountbalance' => array('Character', 1)
        ),
        'account' => array(
            'accountstatus' => array('Character', 33554432)
        ),
        'corp' => array(
            'contracts' => array('Corporation', 8388608),
            'titles' => array('Corporation', 4194304),
            'wallettransactions' => array('Corporation', 2097152),
            'walletjournal' => array('Corporation', 1048576),
            'starbaselist' => array('Corporation', 524288),
            'standings' => array('Corporation', 262144),
            'starbasedetail' => array('Corporation', 131072),
            'shareholders' => array('Corporation', 65536),
            'outpostservicedetail' => array('Corporation', 32768),
            'outpostlist' => array('Corporation', 16384),
            'medals' => array('Corporation', 8192),
            'marketorders' => array('Corporation', 4096),
            'membertracking' => array('Corporation', 2048),
            'membersecuritylog' => array('Corporation', 1024),
            'membersecurity' => array('Corporation', 512),
            'killlog' => array('Corporation', 256),
            'industryjobs' => array('Corporation', 128),
            'facwarstats' => array('Corporation', 64),
            'containerlog' => array('Corporation', 32),
            'contactlist' => array('Corporation', 16),
            'corporationsheet' => array('Corporation', 8),
            'membermedals' => array('Corporation', 4),
            'assetlist' => array('Corporation', 2),
            'accountbalance' => array('Corporation', 1)
        )

    );
}

