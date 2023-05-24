<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use DB;

class DashboradController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // dd(Auth::user());
        //############################################## DYANMIC TOKEN GENERATION #################################################################
        $dynamic_token_body = [
            'grant_type'=> 'client_credentials',
            'client_id'=> '98ed9674-0727-40e0-abee-46b7271c8424',
            'client_secret'=> 'sEAXrJW1FP6bylpACsvlkvE82juQS3F5XNbFSOv5',
            'scope'=> '*'
        ];

        $json_token = json_encode($dynamic_token_body);
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.mdintegrations.xyz/v1/partner/auth/token',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_POSTFIELDS => $json_token,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_HTTPHEADER => array(
            'Accept: application/json',
            'Content-Type: application/json'
        ),
        ));
        $response_token = curl_exec($curl);
        curl_close($curl);
        //############################################### Dynamic Token Generation ends ###############################################
        $token =json_decode($response_token)->access_token;
        //################################################# Get Patient Cases Details ###################################################################################
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.mdintegrations.xyz/v1/partner/patients/61b5fe72-5ebd-465b-a113-31777923414b/cases',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Accept: application/json',
                'Content-Type: application/json',
                'Authorization: Bearer' . ' ' . $token
            )
        ));
        $responsePCase = curl_exec($curl);
        // dd(json_decode($responsePCase)->data);
        $patientCases=json_decode($responsePCase)->data;
        curl_close($curl);
        //############################################### Get Patient Cases Details Ends ###############################################
       $recentOrderCreatedAt=DB::table('patient_case')
       ->select( 'created_at' )
       ->where('userId',2)->orderBy('created_at','desc')
       ->first();
       $recentOrderCreatedAt=date("F j, Y", strtotime($recentOrderCreatedAt->created_at));
        // dd($recentOrderStatus);
        return view('home.index',compact('patientCases','recentOrderCreatedAt'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
