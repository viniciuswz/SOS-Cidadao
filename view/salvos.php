<?php
    if(!file_exists('Config/Config.php')){        
        echo "<script>javascript:window.location='todasreclamacoes';</script>";       
    }
    session_start();
    require_once('Config/Config.php');
    require_once(SITE_ROOT.DS.'autoload.php');   
    use Core\Usuario;
    //use Core\PublicacaoSalva;
    
    try{        
        
        $dadosUrl = explode('/', $_GET['url']);
        
        if(count($dadosUrl) > 1){ // injetou parametros
            throw new \Exception('Não foi possível achar o debate',45);
        }

        $tipoUsuPermi = array('Prefeitura','Adm','Funcionario','Moderador','Comum');
        Usuario::verificarLogin(1,$tipoUsuPermi);  // Tem q estar logado         
        
        $usu = new Usuario();        
        $usu->setCodUsu($_SESSION['id_user']);
        $resultado = $usu->getDadosUser();
        $tipoUsu = $_SESSION['tipo_usu'];
        
       
       
?>
<!DOCTYPE html>
<html lang=pt-br>
    <head>
        <title>Publicações salvas</title>

        <meta charset=UTF-8> <!-- ISO-8859-1 -->
        <meta name=viewport content="width=device-width, initial-scale=1.0">
        <meta name=description content="Site de reclamações para a cidade de Barueri">
        <meta name=keywords content="Reclamação, Barueri"> <!-- Opcional -->
        <meta name=author content='equipe 4 INI3A'>
        <meta name="theme-color" content="#089E8E" />

        <!-- favicon, arquivo de imagem podendo ser 8x8 - 16x16 - 32x32px com extensão .ico -->
        <link rel="shortcut icon" href="view/imagens/favicon.ico" type="image/x-icon">

        <!-- CSS PADRÃO -->
        <link href="view/css/default.css" rel=stylesheet>

        <!-- Telas Responsivas -->
        <link rel=stylesheet media="screen and (max-width:480px)" href="view/css/style480.css">
        <link rel=stylesheet media="screen and (min-width:481px) and (max-width:768px)" href="view/css/style768.css">
        <link rel=stylesheet media="screen and (min-width:769px) and (max-width:1024px)" href="view/css/style1024.css">
        <link rel=stylesheet media="screen and (min-width:1025px)" href="view/css/style1025.css">

        <!-- JS-->

        <script src="view/lib/_jquery/jquery.js"></script>
        <script src="view/js/js.js"></script>
        <script src="view/js/PegarPubliSalva.js"></script>
        <script src="teste.js"></script>

    </head>
    <body onload="jaquinha()">
        <header>
            <a href="todasreclamacoes">
                <img src="view/imagens/logo_oficial.png" alt="logo">
            </a>   
            <i class="icone-pesquisa pesquisa-mobile" id="abrir-pesquisa"></i>
            <form action="pesquisa" method="get" id="form-pesquisa">
                <input type="text" name="pesquisa" id="pesquisa" placeholder="Pesquisar">
                <button type="submit"><i class="icone-pesquisa"></i></button>
            </form>
            <nav class="menu">
                <ul>
                    <li><nav class="notificacoes">
                        <h3>notificações<span id="not-fechado">x</span></h3>
                        <ul id="menu23">
                            
                            <li>
                        </ul>
                    </nav><a href="#" id="abrir-not"><i class="icone-notificacao" id="noti"></i>Notificações</a></li>
                    <li><a href="todasreclamacoes"><i class="icone-reclamacao"></i>Publicações</a></li>
                    <li><a href="todosdebates"><i class="icone-debate"></i>Debates</a></li>
                </ul>
            </nav>
            <?php
                if(!isset($resultado)){
                    echo '<a href="login"><i class="icone-user" id="abrir"></i></a>';
                }else{
                    echo '<i class="icone-user" id="abrir"></i>';
                }
            ?>
        </header>
        <?php
            if(isset($resultado) AND !empty($resultado)){
        ?>
                <div class="user-menu">
                    <a href="javascript:void(0)" class="fechar">&times;</a>
                    <div class="mini-perfil">
                        <div>    
                            <img src="Img/perfil/<?php echo $resultado[0]['img_perfil_usu'] ?>" alt="perfil">
                        </div>    
                            <img src="Img/capa/<?php echo $resultado[0]['img_capa_usu'] ?>" alt="capa">
                            <p><?php echo $resultado[0]['nome_usu'] ?></p>
                    </div>
                    <nav>
                        <ul>
                            <?php
                            require_once('opcoes.php');                        
                            ?>
                        </ul>
                    </nav>
                </div>
        <?php
            }            
        ?>


        <div id="container">
            <div class="salvos">
                <?php
                
                    if(isset($resposta) AND empty($resposta)){                    
                        echo '<h4>Não há publicacões salvas</h4>';                    
                    }else{
                        echo '<h4>Salvos</h4>'; 
                    }
                    
                ?>
            </div>
            <section class="alinha-item" id="pa">
                <div class="modal-denunciar">
                    <div class="modal-denunciar-fundo"></div>
                    <div class="box-denunciar">
                        <div>
                            <h1>Qual o motivo da denúncia?</h1>
                            <span class="fechar-denuncia">&times;</span>
                        </div>
                        
                        <form id="formdenuncia">
                            <textarea placeholder="Qual o motivo?" id="motivo"></textarea>
                            <input type="hidden" name="id_publi" value="">
                            <div class="aviso-form-inicial ">
                                <p>O campo tal e pa</p>
                            </div>
                            <button type="submit"> Denunciar</button>
                        </form>
                    </div>
                </div>          
            </section>
        </div>        
    </body>
</html>
<?php
    }catch (Exception $exc){     
        $erro = $exc->getCode();   
        $mensagem = $exc->getMessage();
        switch($erro){
            case 2://Ja esta logado  
            case 6://Ja esta logado 
                echo "<script>javascript:window.location='todasreclamacoes';</script>";
                break;
            case 45://Digitou um numero maior de parametros 
                unset($dadosUrl[0]);
                $contador = 1;
                $voltar = "";
                while($contador <= count($dadosUrl)){
                    $voltar .= "../";
                    $contador++;
                }
                echo "<script>javascript:window.location='".$voltar."salvos';</script>";
            break;
        
        }      
    }
?>