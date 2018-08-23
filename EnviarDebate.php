<?php
define ('WWW_ROOT', dirname(__FILE__)); 
define ('DS', DIRECTORY_SEPARATOR);
require_once(WWW_ROOT.DS.'autoload.php');
use Core\Debate;
use Classes\ValidarCampos;
use Core\Usuario;
session_start();

try{
    $tipoUsuPermi = array('Comum');
    Usuario::verificarLogin(1,$tipoUsuPermi);  // Tem q estar logado

    
    $nomesCampos = array('imagem','titulo', 'tema','descricao');// Nomes dos campos que receberei do formulario
    $validar = new ValidarCampos($nomesCampos, $_POST, $_FILES);//Verificar se eles existem, se nao existir estoura um erro
    $debate = new Debate();
    $debate->setNomeDeba($_POST['titulo']);
    $debate->setTemaDeba($_POST['tema']);
    $debate->setDescriDeba($_POST['descricao']);            
    $debate->setImgDeba($_FILES['imagem']);
    $debate->setCodUsu($_SESSION['id_user']);
    $debate->insert();   
    $idDeba = $debate->getCodDeba(); // Pegar o codigo inserido, que a classe setou
    echo "<script> javascript:window.location='./Templates/VerDebateTemplate.php?ID=".$idDeba."'</script>";
    
}catch (Exception $exc){
    $erro = $exc->getCode();   
    $mensagem = $exc->getMessage();
    switch($erro){
        case 2://Nao esta logado    
            echo "<script> alert('$mensagem');javascript:window.location='./Templates/loginTemplate.php';</script>";
            break;
        case 6://Não é usuario comum  
            echo "<script> alert('$mensagem');javascript:window.location='./Templates/starter.php';</script>";
            break;
        case 12://Mexeu no insprnsionar elemento
        case 13://Mexeu no debate
            echo "<script> alert('$mensagem');javascript:window.location='./Templates/CriarDebateTemplate.php';</script>";
            break;        
        default: //Qualquer outro erro cai aqui
            echo "<script> alert('$mensagem');javascript:window.location='./Templates/CriarDebateTemplate.php';</script>";
    }    
}   
    