<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectUser;
use App\Models\ProjectReport;
use App\Models\ProjectUtilReport;
use App\Models\Restriction;
use App\Models\RestrictionMember;
use App\Models\User;
use Illuminate\Testing\Fluent\Concerns\Has;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProjectController extends Controller
{
    //
    public function create_project (Request $request) {

        $data = $request->validate([
            'projectName' => 'required|string',
            'business' => 'required|numeric',
            'projectType' => 'required|numeric',
            'district' => 'required|numeric',
            'country' => 'required|string',
        ]);

        $codPro = Project::insertGetId([
            'desNombreProyecto' => $request['projectName'],
            'codEstado' => 1,
            'desEmpresa' => $request['business'],
            'numPlazo' => intval($request['term']),
            'id' => $request['id'] | 0,
            'numAreaTechado' => intval($request['coveredArea']),
            'codTipoProyecto' => intval($request['projectType']),
            'codUbigeo' => intval($request['district']),
            'dayFechaInicio' => $request['startDate'],
            'numMontoReferencial' => (float) $request['referenceAmount'],
            'numAreaTechada' => intval($request['area']),
            'numAreaConstruida' => intval($request['builtArea']),
            'desPais' => $request['country'],
            'desDireccion' => $request['address'],
            'dayFechaCreacion' => $request['date'],
            'desUsuarioCreacion' => $request['usersum'],
            'codMoneda' => intval($request['codMoneda'])
        ]);

        foreach($request['userInvData'] as $user) {

            $userEmail = $user['userEmail'];
            $userRole  = $user['userRole'];
            $userArea  = $user['userArea'];

            if(ltrim(rtrim($userEmail)) != '' &&  ltrim(rtrim($userRole)) != '' && ltrim(rtrim($userRole)) != '')
            {

                $usercreate = ProjectUser::create([
                    'codProyecto'         => $codPro,
                    'id'                  => $request['id'],
                    'codEstadoInvitacion' => 1,
                    'codRolIntegrante'    => intval($userRole),
                    'dayFechaInvitacion'  => $request['date'],
                    'codArea'             => intval($userArea),
                    'desCorreo'           => strval($userEmail),
                ]);

            }
        }


        $useremail = User::where('id', $request['id'])->get('email');
        $restrictioncreate = Restriction::create([
            'codProyecto' => $codPro,
            'codEstado' => 1,
            'dayFechaCreacion' => $request['date'],
            'desUsuarioCreacion' => $useremail[0]['email'],
            'indNoRetrasados' => 0,
            'indRetrasados' => 0,
        ]);
        /* $restrictionid = Restriction::where('codProyecto', $codPro[0]['codProyecto'])->get('codAnaRes');
        $restrictionmember = RestrictionMember::create([
            'codProyecto' => $codPro[0]['codProyecto'],
            'codAnaRes' => $restrictionid[0]['codAnaRes'],
            'codEstado' => 1,
            'dayFechaCreacion' => $request['date'],
            'desUsuarioCreacion' => '',
        ]); */
        foreach($request['reports'] as $report) {
            $utilreportcreate = ProjectUtilReport::create([
                'desUtilReportes' => $report['reportSchedule']
            ]);
            $codUtilPro = ProjectUtilReport::get('codUtilReportes');
            $cod = $codUtilPro[sizeof($codUtilPro)-1]['codUtilReportes'];
            if($report['massiveStatus'] === 1) {
                $reportcreate = ProjectReport::create([
                    'codUtilReportes' => $cod,
                    'codProyecto' => $codPro,
                    'flagReporteMasivo' => $report['massiveStatus'],
                    'codTipoFrecuencia' => $request['typeFrequency'],
                    'dayFechaCreacion' => $request['date'],
                    'desUsuarioCreacion' => '',
                    'desCorreoEnvios' => '',
                    'codfrecuenciaenvioreporte' => $report['frequency']
                ]);
            }
            else {
                foreach($report['frequencies'] as $frequency) {
                    $reportcreate = ProjectReport::create([
                        'codUtilReportes' => $cod,
                        'codProyecto' => $codPro,
                        'flagReporteMasivo' => $report['massiveStatus'],
                        'codTipoFrecuencia' => $request['typeFrequency'],
                        'dayFechaCreacion' => $request['date'],
                        'desUsuarioCreacion' => '',
                        'desCorreoEnvios' => $frequency['user'],
                        'codfrecuenciaenvioreporte' => $frequency['freq']
                    ]);
                }
            }
        }

        return $codPro;
    }

    public function get_project (Request $request) {
        // $project = Project::where('id', $request['id'])->get();

        $project = Project::select('proy_proyecto.*', 'conf_maestro_empresas.des_Empresa as nombreEmpresa', 'conf_ubigeo.desUbigeo as desUbigeo')
        ->join('conf_maestro_empresas', 'proy_proyecto.desEmpresa', '=', 'conf_maestro_empresas.cod_Empresa')
        ->join('conf_ubigeo', 'proy_proyecto.codUbigeo', '=', 'conf_ubigeo.codUbigeo')
        ->where('proy_proyecto.id', $request['id'])
        ->get();

        return $project;
    }
    public function edit_project (Request $request) {
        // $timestamp = strtotime($request['startDate']);
        // $fechaInicio  = date("d-m-Y", $timestamp);

        $update = Project::where('codProyecto', $request['projectId'])->update([
            'desNombreProyecto' => $request['projectName'],
            'codEstado' => 1,
            'desEmpresa' => $request['business'],
            'numPlazo' => intval($request['term']),
            'numAreaTechado' => intval($request['coveredArea']),
            'codTipoProyecto' => intval($request['projectType']),
            'codUbigeo' => intval($request['district']),
            'dayFechaInicio' => $request['startDate'],
            'numMontoReferencial' => intval($request['referenceAmount']),
            'numAreaTechada' => intval($request['area']),
            'numAreaConstruida' => intval($request['builtArea']),
            'desPais' => $request['country'],
            'desDireccion' => $request['address'],
            'dayFechaCreacion' => $request['date'],
            'desUsuarioCreacion' => $request['usersum'],
            'codMoneda' => $request['codMoneda']
        ]);

        ProjectUser::where('codProyecto',$request['projectId'])->delete();
        foreach($request['userInvData'] as $user) {

            $userEmail = $user['userEmail'];
            $userRole  = $user['userRole'];
            $userArea  = $user['userArea'];

            if(ltrim(rtrim($userEmail)) != '' &&  ltrim(rtrim($userRole)) != '' && ltrim(rtrim($userRole)) != '')
            {

                $usercreate = ProjectUser::create([
                    'codProyecto'         => $request['projectId'],
                    'id'                  => $request['id'],
                    'codEstadoInvitacion' => 1,
                    'codRolIntegrante'    => intval($userRole),
                    'dayFechaInvitacion'  => $request['date'],
                    'codArea'             => intval($userArea),
                    'desCorreo'           => strval($userEmail),
                ]);

            }
        }



        return $request['projectId'];
    }
    public function get_projectreport (Request $request) {
        $project = ProjectReport::where('codProyecto', $request['projectId'])->get();
        return $project;
    }
    public function get_projectuser (Request $request) {
        $projectuser = ProjectUser::where('codProyecto', $request['projectId'])->get();
        return $projectuser;
    }
}
