<?php

namespace App\Http\Controllers;
use App\Models\RestrictionMember;
use App\Models\Restriction;
use App\Models\ProjectUser;
use App\Models\PhaseActividad;
use App\Models\RestrictionPerson;
use App\Models\RestrictionFront;
use App\Models\RestrictionPhase;
use App\Models\Conf_Estado;
use App\Models\Ana_TipoRestricciones;
use App\Models\Proy_AreaIntegrante;

use Illuminate\Http\Request;




class RestrictionController extends Controller
{
    //
    public function update_member(Request $request) {
        $restrictionid = Restriction::where('codProyecto', $request['projectId'])->get('codAnaRes');

        $checkuser = RestrictionMember::where('codProyecto', $request['projectId'])->delete();
        foreach($request['users'] as $user) {
            $projectuserid = ProjectUser::where('codProyecto', $request['projectId'])->where('desCorreo', $user)->get('codProyIntegrante');
            $restrictionmember = RestrictionMember::create([
                'codProyecto' => $request['projectId'],
                'codAnaRes' => $restrictionid[0]['codAnaRes'],
                'codEstado' => 1,
                'dayFechaCreacion' => $request['date'],
                'desUsuarioCreacion' => '',
                'codProyIntegrante' => $projectuserid[0]['codProyIntegrante']
            ]);
        }

        /* $res = RestrictionMember::where('codProyecto', $request['projectId'])->update([
            'desUsuarioCreacion' => $request['users']
        ]); */
        return $restrictionid;
    }
    public function add_front(Request $request) {
        $codAnaRes = Restriction::where('codProyecto', $request['id'])->get('codAnaRes');
        $resFrente = RestrictionFront::insertGetId([
            'desAnaResFrente' => $request['name'],
            'dayFechaCreacion' => $request['date'],
            'desUsuarioCreacion' => '',
            'codProyecto' => $request['id'],
            'codAnaRes' => $codAnaRes[0]['codAnaRes'],
        ]);
        $enviar = array();
        $enviar["codFrente"] = $resFrente;
        return $enviar;
    }

    public function add_phase(Request $request) {
        $codAnaRes = Restriction::where('codProyecto', $request['projectid'])->get('codAnaRes');
        $resFase  = RestrictionPhase::insertGetId([
            'desAnaResFase' => $request['name'],
            'dayFechaCreacion' => $request['date'],
            'desUsuarioCreacion' => '',
            'codAnaResFrente' => $request['frontid'],
            'codProyecto' => $request['projectid'],
            'codAnaRes' => $codAnaRes[0]['codAnaRes'],
        ]);
        $enviar = array();
        $enviar["codFase"] = $resFase;
        return $enviar;
    }

    public function add_Actividad(Request $request) {
        $codAnaRes = Restriction::where('codProyecto', $request['projectid'])->get('codAnaRes');

        $resFront = PhaseActividad::create([
            'desActividad' => $request['name'],
            'desRestriccion' => "",
            'codTipoRestriccion' => 0,
            'dayFechaRequerida' => $request['date'],
            // 'idUsuarioResponsable' => 'Lizeth Marzano',
            // 'desAreaResponsable' => 'Lizeth Marzano',
            // 'codEstadoActividad' => 'En proceso',
            // 'codUsuarioSolicitante' => 'Lizeth Marzano',
            'codAnaResFase' => $request['phaseid'],
            'codAnaResFrente' => $request['frontid'],
            'codProyecto' => $request['projectid'],
            'codAnaRes' => $codAnaRes[0]['codAnaRes'],
        ]);
        return $request;
    }

    // public function upd_restricciones(Request $request){
    //     $enviar = array();
    //     $enviar["flag"]     = 0;
    //     $enviar["mensaje"]  = "";
    //     $data   = $request['userInvData']

    //     foreach ($request as $value1) {
    //         //  print_r($value['desActividad']);

    //         // foreach ($value1 as  $value2) {
    //             // echo  $value2['desActividad'];
    //             $enviar["mensaje"]  = $value1['desActividad'];
    //             // break;
    //             // $enviar["mensaje"]  = $value2['desActividad'];
    //         // }
    //         // break;
    //     }

    //     return $enviar;
    // }

    public function upd_restricciones (Request $request){

        // $data = json_decode($request);
        $enviar = array();
        $enviar["flag"]     = 0;
        $enviar["mensaje"]  = "";
        // print_r($request);

        try {
            // print_r($request);
            foreach ($request['data'] as $value) {


                    $fecha     = str_replace("T", " ", $value['dayFechaRequerida']);
                    $fecha     = str_replace("Z", "", $fecha);

                    $fechac    = str_replace("T", " ", $value['dayFechaConciliada']);
                    $fechac    = str_replace("Z", "", $fechac);
                    $resultado = "";
                    $tiporesultado = "";

                    if($value['codAnaResActividad'] != -999){

                        $resultado = PhaseActividad::where('codAnaResActividad',(int)$value['codAnaResActividad'])->update([
                            'codTipoRestriccion' => $value['codTipoRestriccion'],
                            'desActividad'       => (string)$value['desActividad'],
                            'desRestriccion'     => (string)$value['desRestriccion'],
                            'idUsuarioResponsable'   => $value['idUsuarioResponsable'],
                            'codEstadoActividad'     => $value['codEstadoActividad'],
                            'dayFechaRequerida'      => ($fecha == 'null' || $fecha == '') ? null : $fecha,
                            'dayFechaConciliada'     => ($fechac == 'null' || $fechac == '') ? null : $fechac,
                        ]);
                        $tiporesultado = "upd";

                    }else{

                        $codAnaRes = Restriction::where('codProyecto', $request['projectId'])->get('codAnaRes');
                        $resultado = PhaseActividad::create([
                            'codTipoRestriccion' => $value['codTipoRestriccion'],
                            'desActividad'       => (string)$value['desActividad'],
                            'desRestriccion'     => (string)$value['desRestriccion'],
                            'idUsuarioResponsable'   => $value['idUsuarioResponsable'],
                            'codEstadoActividad'     => $value['codEstadoActividad'],
                            'dayFechaRequerida'      => ($fecha == 'null' || $fecha == '') ? null : $fecha,
                            'dayFechaConciliada'     => ($fechac == 'null' || $fechac == '') ? null : $fechac,
                            'codProyecto'   => $request['projectId'],
                            'codAnaRes'     => $codAnaRes[0]['codAnaRes'],
                            'codAnaResFase' => $value['codAnaResFase'],
                            'codAnaResFrente' => $value['codAnaResFrente'],
                            'codUsuarioSolicitante' => $request['userId']
                        ]);
                        $tiporesultado = "ins";

                    }

                    $enviar["updateResult"] = $resultado;
                    $enviar["typeResult"]   = $tiporesultado;
                    $enviar["mensaje"]      = "Registros Actualizados !";
                    $enviar["flag"]         =  1;


            }


        } catch (Throwable $e) {

            $enviar["mensaje"]  = $e;

        }

         return $enviar;

    }

    public function get_restrictionsMember(Request $request){
        $devolvemos_data = RestrictionMember::select("ana_integrantes.*", "proy_integrantes.desCorreo as desProyIntegrante", "proy_integrantes.codArea")
        ->leftJoin('proy_integrantes', function($join){
            $join->on('proy_integrantes.codProyIntegrante', '=', 'ana_integrantes.codProyIntegrante');
            $join->on('proy_integrantes.codProyecto', '=', 'ana_integrantes.codProyecto');

         })
         ->where('ana_integrantes.codProyecto', $request['id'])->get();
        // ->join('anares_analisisrestricciones', 'ana_integrantes.codAnaRes', '=', 'anares_analisisrestricciones.codAnaRes')
        // ->join('proy_integrantes', 'proy_integrantes.codProyIntegrante', '=', 'ana_integrantes.codProyIntegrante', ' and ','ana_integrantes.codProyecto','=','ana_integrantes.codProyecto')
        // ->where('anares_analisisrestricciones.codProyecto', $request['id'])->get();
        return $devolvemos_data;
    }

    public function get_estado(Request $request) {

        $datos_estado = Conf_Estado::all();

        return $datos_estado;
    }

    public function get_data_restricciones(Request $request) {
        $frontdata   = RestrictionFront::where('codProyecto', $request['id'])->get();
        $restriction = Restriction::where('codProyecto', $request['id'])->get();

        $enviar      = array();
        $anaresdata  = [];

        foreach($frontdata as $eachdata) {
            $dataFrente = [
                'codFrente'     => $eachdata['codAnaResFrente'],
                'desFrente'   => $eachdata['desAnaResFrente'],
                'isOpen' => true,
                'listaFase'   => [],
            ];

            $phasedata = RestrictionPhase::where('codAnaResFrente', $eachdata['codAnaResFrente'])->get();
            foreach($phasedata as $sevdata) {
                $dataFase = [
                    'codFase' => $sevdata['codAnaResFase'],
                    'desFase' => $sevdata['desAnaResFase'],
                    // 'notDelayed' => $restriction[0]['indNoRetrasados'],
                    // 'delayed' => $restriction[0]['indRetrasados'],
                    'listaRestricciones' => [],
                    'hideCols' => [],
                ];
                $Activedata = PhaseActividad::select("anares_actividad.*" , "anares_tiporestricciones.desTipoRestricciones as desTipoRestriccion" , "proy_integrantes.desCorreo as desUsuarioResponsable", "proy_areaintegrante.desArea", "conf_estado.desEstado as desEstadoActividad")
                ->leftjoin('anares_tiporestricciones', 'anares_actividad.codTipoRestriccion', '=', 'anares_tiporestricciones.codTipoRestricciones')
                ->leftJoin('proy_integrantes', function($join){
                    $join->on('proy_integrantes.codProyIntegrante', '=', 'anares_actividad.idUsuarioResponsable');
                    $join->on('proy_integrantes.codProyecto', '=', 'anares_actividad.codProyecto');
                 })
                 ->leftJoin('proy_areaintegrante', function($join){
                    $join->on('proy_integrantes.codArea', '=', 'proy_areaintegrante.codArea');
                 })
                 ->leftJoin('conf_estado', function($join){
                    $join->on('anares_actividad.codEstadoActividad', '=', 'conf_estado.codEstado');
                 })

                ->where('anares_actividad.codAnaResFase','=',  $sevdata['codAnaResFase'])
                ->where('anares_actividad.codAnaResFrente','=', $eachdata['codAnaResFrente'])
                ->get();
                    foreach($Activedata as $data) {
                        $restricciones = [
                            'codAnaResActividad' => $data['codAnaResActividad'],
                            'desActividad'       => $data['desActividad'],
                            'desRestriccion'     => $data['desRestriccion'],
                            'codTipoRestriccion' => $data['codTipoRestriccion'],
                            'desTipoRestriccion' => $data['desTipoRestriccion'],
                            'dayFechaRequerida'     => $data['dayFechaRequerida'] == null ? '' : $data['dayFechaRequerida'],
                            'dayFechaConciliada'    => $data['dayFechaConciliada'] == null ? '' : $data['dayFechaConciliada'],
                            'idUsuarioResponsable'  => $data['idUsuarioResponsable'],
                            'desUsuarioResponsable' => $data['desUsuarioResponsable'],
                            'codEstadoActividad' => $data['codEstadoActividad'],
                            'desEstadoActividad' => $data['desEstadoActividad'],
                            'desAreaResponsable' => $data['desArea'],
                            'isEnabled'          => false,
                            'isupdate'           => false
                            // 'applicant' => "Lizeth Marzano",
                        ];
                        array_push($dataFase['listaRestricciones'], $restricciones);
                    }
                array_push($dataFrente['listaFase'], $dataFase);
            }
            array_push($anaresdata, $dataFrente);
        }

        $tipoRestricciones = Ana_TipoRestricciones::All();
        $areaIntegrante    = Proy_AreaIntegrante::all();
        $integrantesAnaRes = RestrictionMember::select("ana_integrantes.*", "proy_integrantes.desCorreo as desProyIntegrante", "proy_integrantes.codArea")
        ->leftJoin('proy_integrantes', function($join){
            $join->on('proy_integrantes.codProyIntegrante', '=', 'ana_integrantes.codProyIntegrante');
            $join->on('proy_integrantes.codProyecto', '=', 'ana_integrantes.codProyecto');

         })
         ->where('ana_integrantes.codProyecto', $request['id'])->get();
        $datos_estado = Conf_Estado::all();

        $enviar['estados']           = $datos_estado;
        $enviar['integrantesAnaReS'] = $integrantesAnaRes;
        $enviar['areaIntegrante']    = $areaIntegrante;
        $enviar['tipoRestricciones'] = $tipoRestricciones;
        $enviar['restricciones']     = $anaresdata;



        return $enviar;
    }
    public function get_front(Request $request) {
        $frontdata = RestrictionFront::where('codProyecto', $request['id'])->get();
        $restriction = Restriction::where('codProyecto', $request['id'])->get();
        // $frontdata = RestrictionFront::where('codProyecto', '107')->get();
        // $restriction = Restriction::where('codProyecto', '107')->get();
        $anaresdata = [];

        foreach($frontdata as $eachdata) {
            $tempdata = [
                'id' => $eachdata['codAnaResFrente'],
                'name' => $eachdata['desAnaResFrente'],
                'isOpen' => false,
                'info' => [],
            ];

            $phasedata = RestrictionPhase::where('codAnaResFrente', $eachdata['codAnaResFrente'])->get();
            foreach($phasedata as $sevdata) {
                $temp = [
                    'id' => $sevdata['codAnaResFase'],
                    'name' => $sevdata['desAnaResFase'],
                    'notDelayed' => $restriction[0]['indNoRetrasados'],
                    'delayed' => $restriction[0]['indRetrasados'],
                    'tableData' => [],
                    'hideCols' => [],
                ];
                $Activedata = PhaseActividad::where('codAnaResFase','=',  $sevdata['codAnaResFase'])
                                    ->where('codAnaResFrente','=', $eachdata['codAnaResFrente'])
                                    ->get();
                    foreach($Activedata as $data) {
                        $temptable = [
                            'exercise' => $data['desActividad'],
                            // 'restriction' => $data['desRestriccion'],
                            'restriction' => "restriction",
                            'date_required' =>$data['dayFechaRequerida'],
                            'responsible' => $data['desActividad'],
                            'responsible_area' => "Arquitectura",
                            'applicant' => "Lizeth Marzano",
                        ];
                        array_push($temp['tableData'], $temptable);
                    }
                array_push($tempdata['info'], $temp);
            }
            array_push($anaresdata, $tempdata);
        }
        return $anaresdata;
    }


    public function delete_restriction(Request $request){
        $resultado = array();
        $resultado['flag']          = 0;
        $resultado['resultado']     = "";

        try {

            $resultado['resultado'] =  PhaseActividad::where('codAnaResActividad', $request['codAnaResActividad'])->delete();
            $resultado['flag']      =  1;

        } catch (\Throwable $th) {

            $resultado['resultado'] =  $th;


        }

      return $resultado;
    }

    public function duplicate_restriction(Request $request){
        $resultado = array();
        $resultado['flag']          = 0;
        $resultado['resultado']     = "";

        try {

            // $consulta  =  PhaseActividad::where('codAnaResActividad', 19)->get();
            // $consulta2 = $consulta->replicate()->save();
            // $consulta2 = $consulta2->codAnaResActividad;
            $consulta        = PhaseActividad::find($request['codAnaResActividad'])->replicate();
            $arrayconsulta   = $consulta->toArray();
            $newCreatedModel = PhaseActividad::create($arrayconsulta);
            // $newID      = $consulta->id;

            $resultado['resultado'] =  $newCreatedModel['codAnaResActividad'];
            $resultado['flag']      =  1;

        } catch (\Throwable $th) {

            $resultado['resultado'] =  $th;


        }

      return $resultado;
    }

    public function delete_front(Request $request) {

        // if ($request['phaseLen'] == 0 || $request['phaseId'] == '-999') {

        if ($request['phaseId'] == '-999' || $request['phaseId'] == 0 || $request['phaseId'] == null) {

            try {

            PhaseActividad::where('codAnaResFrente', $request['frontId'])->delete();
            RestrictionPhase::where('codAnaResFrente', $request['frontId'])->delete();
            RestrictionFront::where('codAnaResFrente', $request['frontId'])->delete();

                 }
            catch (\Throwable $th) {}
            // RestrictionFront::where('codAnaResFrente', $request['frontId'])->delete();
            // try {
            //     RestrictionPhase::where('codAnaResFrente', $eachdata['frontId'])->delete();
            // } catch (\Throwable $th) {}

        } else {

            try {
            PhaseActividad::where('codAnaResFase', $request['phaseId'])->delete();
            } catch (\Throwable $th) {}
            RestrictionPhase::where('codAnaResFase', $request['phaseId'])->delete();
            // $frontdata = RestrictionFront::where('codAnaResFrente', $request['frontId'])->get();
            // foreach($frontdata as $eachdata) {
            //     RestrictionPhase::where('codAnaResFase', $request['phaseId'])->delete();
            // }
        }
        return $request;
    }

    public function get_restriction_p(Request $request) {
        $TipoRestricciones = RestrictionPerson::where('codTipoRestricciones', '>=', 0)->get();
        return $TipoRestricciones;
    }

    public function add_restriction(Request $request) {
        $TipoRestricciones = RestrictionPerson::where('codTipoRestricciones', '>=', 0)->delete();
        $index = $request['index'];
        for ($i=0; $i < $index; $i++) {
            if($request['data'][$i]['value'] == '') {
                $i -= 1;
                $index -= 1;
            }
            else {
                $resFront = RestrictionPerson::create([
                    'codTipoRestricciones' => $i,
                    'desTipoRestricciones' => $request['data'][$i]['value'],
                ]);
            }
        }
        $TipoRestricciones = RestrictionPerson::where('codTipoRestricciones', '>=', 0)->get();
        return $TipoRestricciones;
    }
}
