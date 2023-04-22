<?php
session_start();
// session_destroy();
function filesize_formatted($size) { $units = array( 'B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'); $power = $size > 0 ? floor(log($size, 1024)) : 0; return number_format($size / pow(1024, $power), 2, '.', ',') . ' ' . $units[$power]; }


if($_POST['acti']=='info'){
    
    $_SESSION["reducir"]= array();
    $_SESSION["list"] = ((is_array($_SESSION["list"]) and !empty($_SESSION["list"]))? $_SESSION["list"]: array());
    $_SESSION["e"]=100;
    $_SESSION["a"]= 0;
    $_SESSION["b"] = 0;
    $_SESSION["v"]=0;
    function info_total($ruta){
        $img= array('jpeg','jpg','gif','png','tiff','psd','cr2');
        if (is_dir($ruta)){
            $gestor = opendir($ruta);
            while (($archivo = readdir($gestor)) !== false)  {
                $ruta_completa = $ruta . "/" . $archivo;
                if ($archivo != "." && $archivo != "..") {
                    if (is_dir($ruta_completa)) {
                        info_total($ruta_completa);
                    } else {
                        $ext = pathinfo($archivo, PATHINFO_EXTENSION);
                        if(in_array($ext,$img)){
                            if(is_array($_SESSION["list"]) and !empty($_SESSION["list"]) and in_array($ruta_completa, $_SESSION["list"])){
                                    
                                unset($_SESSION["reducir"][$_SESSION["a"]][$_SESSION["b"]]);
                            }else{
                                $_SESSION["reducir"][$_SESSION["a"]][$_SESSION["b"]]=$ruta_completa;
                                if($_SESSION["b"]==$_SESSION["e"]){  
                                    $_SESSION["e"]=$_SESSION["e"]+100;
                                    $_SESSION["a"]++;
                                }
                                $_SESSION["b"]++;
                            }
                            
                        }
                    }
                }
                
            }
            
            closedir($gestor);
        }
        $data = array('reducir'=>$_SESSION["reducir"],'cantidad'=>$_SESSION["a"]);
        return $data;
    }


    info_total(Basedir);

    wp_die(json_encode(array('actibtn'=>true,'reducc'=>$_SESSION["reducir"],'indicea'=>$_SESSION["a"],'indiceb'=>$_SESSION["b"],'peso'=> filesize_formatted($_SESSION["pesodest"]))));
    
}else if($_POST['acti']=='reducir'){

    function reduccion($ruta){
        if(isset($_SESSION["list"])){
            $_SESSION["v"] = count($_SESSION["list"])+1;
        }
        define('WEBSERVICE', 'http://api.resmush.it/ws.php?img=');
        foreach ($ruta as $rutar) {
            $rutaurl = str_replace(Basedir,Baseurl, $rutar);
            $o = json_decode(file_get_contents(WEBSERVICE .$rutaurl.'&qlty=80'));
            if (!isset($o->error)) {
                $_SESSION["list"][$_SESSION["v"]] = $rutar;
                $ruta_copy = str_replace(Baseurl,Basedir, $rutar);
                copy($o->dest, $ruta_copy);
                
                $_SESSION["pesosrc"] = $_SESSION["pesosrc"] + $o->src_size;
                $_SESSION["pesodest"] = $_SESSION["pesodest"] + $o->dest_size;
                $pesosrc = $pesosrc + $o->src_size;
                $pesodest = $pesodest + $o->dest_size;
            }
            $_SESSION["v"]++;
            
        }
                        
        $data = array('src'=> $_SESSION["pesosrc"], 'dest'=> $_SESSION["pesodest"],'srcm'=> $pesosrc, 'destm'=> $pesodest,'reducir'=>$_SESSION["reducir"]);
        return $data;
    }
    
    $_SESSION["archivo"] = $_SESSION["archivo"]+100;
    $_SESSION["archivos"] = $_SESSION["b"] - $_SESSION["archivo"];
    
    
    $_SESSION["rrr"] = $_SESSION["a"] - $_SESSION["rr"];
    
    $e = reduccion($_SESSION["reducir"][$_SESSION["rrr"]]);
    
    $_SESSION["rr"] = $_SESSION["rr"]+1;
    $peso = 'Optimizando '.filesize_formatted($e['srcm']).'/'.filesize_formatted($e['destm']).' - '.$_SESSION["archivos"];
    
    if($_SESSION["archivos"]<=0){
        $_SESSION["archivo"] = 0;
        $_SESSION["rr"]=0;
        $_SESSION["reducir"]=array();
        $_SESSION["a"]=0;
        $_SESSION["v"]=0;
        $peso = 'Optimizado '.filesize_formatted($_SESSION["pesodest"]);
        wp_die(json_encode(array('actibtn'=>false,'peso'=>$peso,'list'=>$_SESSION["list"],'indicea'=>$_SESSION["a"],'rr'=>$_SESSION["rr"],'rrr'=>$_SESSION['rrr'],'archivos'=>$_SESSION["archivos"] )));
    }else{
        wp_die(json_encode(array('actibtn'=>true,'peso'=>$peso,'rrr'=>$_SESSION["rrr"],'list'=>$_SESSION["list"],'srcm'=>$e['srcm'],'destm'=>$e['destm'])));
    }
}