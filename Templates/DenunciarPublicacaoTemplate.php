<?php
session_start();
    $NomeArquivo = dirname(__FILE__);
    $posicao = strripos($NomeArquivo, "\Templates");
    if($posicao){
        $NomeArquivo = substr($NomeArquivo, 0, $posicao);
    }
    define ('WWW_ROOT', $NomeArquivo); 
    define ('DS', DIRECTORY_SEPARATOR);    
    require_once('../autoload.php');
    
    use Core\Usuario;
    use Classes\ValidarCampos;
    try{
        Usuario::verificarLogin(2);//Tem q estar logado
        Usuario::verificarLogin(8);//Apenas user comum, prefeitura e func 

        $nomesCampos = array('ID');// Nomes dos campos que receberei da URL    
        $validar = new ValidarCampos($nomesCampos, $_GET);
        $validar->verificarTipoInt($nomesCampos, $_GET); // Verificar se o parametro da url é um numero        
?>

<html>
    <head>
        <title>Denunciar Publicacao</title>
    </head>
    <body>
        <form method="post" action="../DenunciarPublicacao.php">
            <textarea type="text" name="texto" required cols="75" rows="10" placeholder="Escreva uma descrição"></textarea>
                <br>
            <input type="hidden" name="id_publi" value="<?php echo $_GET['ID'] ?>">                
            <input type="submit" value="Enviar">
        </form>
    </body>
</html>

<?php
    }catch (Exception $exc){
        $erro = $exc->getCode();   
        $mensagem = $exc->getMessage();
        switch($erro){
            case 2://Ja esta logado   
                echo "<script> alert('$mensagem');javascript:window.location='starter.php';</script>";
                break;
            case 6: // Não esta autorizado
            case 12://Mexeu no insprnsionar elemento, ou mexeu no valor do id           
                echo "<script> alert('$mensagem');javascript:window.location='VisualizarPublicacoesTemplate.php';</script>";
            break; 
           
        }         
    }
?>