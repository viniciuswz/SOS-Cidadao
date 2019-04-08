<?php
    if(!file_exists('Config/Config.php')){        
        echo "<script>javascript:window.location='todasreclamacoes';</script>";       
    }
    session_start();
    require_once('Config/Config.php');
    require_once(SITE_ROOT.DS.'autoload.php');
        
    use Core\Usuario;
    try{
        $tipoUsuPermi = array('Comum');        
        Usuario::verificarLogin(1,$tipoUsuPermi);  // 1 = tem q estar logado
        
        $dados = new Usuario();
        $dados->setCodUsu($_SESSION['id_user']);
        $resultado = $dados->getDadosUser(); 
           
        $dadosUrl = explode('/', $_GET['url']);
        if(count($dadosUrl) > 1){ // injetou parametros
            throw new \Exception('Não foi possível achar o debate',45);
        }
?>
<!DOCTYPE html>
<html lang=pt-br>
    <head>
        <title>Enviar um Debate.</title>

        <meta charset=UTF-8> <!-- ISO-8859-1 -->
        <meta name=viewport content="width=device-width, initial-scale=1.0">
        <meta name=description content="Envie um debate, chame a galera para discutir algo da cidade, Entre já!">
        <meta name=keywords content="Debate, Comunidade, Discussão, Barueri"> <!-- Opcional -->
        <meta name=author content='equipe 4 INI3A'>
        <meta name="theme-color" content="#089E8E" />

        <!-- favicon, arquivo de imagem podendo ser 8x8 - 16x16 - 32x32px com extensão .ico -->
        <link rel="shortcut icon" href="view/imagens/favicon.ico" type="view/image/x-icon">

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
        <script src="teste.js"></script>

        <!-- cropp-->

        <link rel="stylesheet" href="view/lib/_croppie-master/croppie.css">
        <script src="view/lib/_croppie-master/croppie.js"></script>
        <script src="view/lib/_croppie-master/exif.js"></script>

    </head>
    <body>
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
                    <li><a href="todosdebates"><i class="icone-debate"></i>Fórum</a></li>
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

   
            <form class="formulario debateForm" name="envio debate" method="post" action="EnviarDebate.php" enctype="multipart/form-data">
                <!--FORMULARIO ENVIO TITULO E TEMA-->
                <div class="informacoes">
                    <h3 id="bacon">Informações importantes</h3>
                    <hr>
                        <div class="campo-envio">
                            <label for="titulo">Título<p></p></label>
                            <input type="text" id="titulo" name="titulo" placeholder="ex. Trânsito Lento" autocomplete="off">
                            <span></span>
                
                    </div>
                        
                        <div class="campo-envio">
                            <label for="tema">Tema<p></p></label>
                            <input type="text" id="tema" name="tema" placeholder="ex. Trânsito" autocomplete="off">
                            <span></span>
                
                        </div>
                    </div>
                            
                    <!--FORMULARIO ENVIO DA FOTO-->           
                    <div class="imagem">
                        <h3>Escolha uma foto para o debate</h3>
                        <hr>
                        <div class="envio-img">
                                            
                            <input type="file" accept="image/*" id="imagemDebateInput">
                            <label id="abrir-cortar"><p><i class="icone-camera"></i>Escolha foto</p>
                            <input type="hidden" id="base64" name="base64" value="banana">
                                
                        <div>
                            <img src="" id="imgPreview">
                        </div>
                            </label>
                        </div>
                        <p></p>
                    </div>
                                
                    <!--FORMULARIO DESCRIÇÃO DO DEBATE--> 
                        <div class="campo-texto"> 
                            <h3>Sobre o que vai debater ?</h3>
                        
                        <hr>
                    
                        
                            <textarea placeholder="Escreva aqui" id="sobre" name="descricao"></textarea>
                                <p></p>
                                <input type="submit" value="Iniciar debate" class="cta">
                    
                        </div>     
            </form>

 

        </div>
        <div class="modal-troca-foto-reclamacao">
            <div class="modal-troca-foto-reclamacao-fundo"></div>
            <div class="box-troca-foto-reclamacao">
                <div class="modal-titulo">
                    <h1>escolha uma foto para o debate</h1>
                    <span class="fechar-troca-foto-reclamacao">&times;</span>
                </div>
                <div class="img-reclamacao-corta">
                   <img src="">
                </div>
                <div>  
                <div class="aviso-form-inicial ">
                    <p>O campo tal e pa</p>
                </div>               
                   
                        <label for="imagemDebateInput"><p>Escolher foto</p></label>
                    
                    <button id="cortarDebate">Cortar foto</button>
                </div>
            </div>
        </div> 

        <div class="modal-troca-foto-reclamacao-previa">
            <div class="modal-troca-foto-reclamacao-previa-fundo"></div>
            <div class="box-troca-foto-reclamacao-previa">
                <div>
                    <h1>Gostou?</h1>
                    <span class="fechar-troca-foto-reclamacao-previa">&times;</span>
                </div>
                <div>                 
                    <img src="" class="previewReclamacao" style="width: 100%">
                    <button class="alterar-reclamacao">Gostei</button>

                    <button class="outra-reclamacao">tentar de novo</button>
                </div>
            </div>
        </div>
        <script>
            
            var largura= $(document).width();
            if(largura < 420){
                var tela= $(document).width();
            }else{
                var tela= 420
            }
            
            var  $uploadCropReclamacao = $('.img-reclamacao-corta').croppie({
                
                enableExif: true,
                enforceBoundary:true,
                enableOrientation:true,
                enableResize:false,
                viewport: {
                    width: 280,
                    height: 190,
                    
                },
                boundary: {
                    width: tela,
                    height: 300
                },
            });
        </script> 
    </body>
</html>
<?php
    }catch (Exception $exc){
        $erro = $exc->getCode();   
        $mensagem = $exc->getMessage();  
        switch($erro){
            case 2://Nao esta logado   
                echo "<script>javascript:window.location='login';</script>";
                break;
            case 6://Não é usuario comum                 
                echo "<script>javascript:window.location='todosdebates';</script>";
                break;            
            case 45://Digitou um numero maior de parametros 
                unset($dadosUrl[0]);
                $contador = 1;
                $voltar = "";
                while($contador <= count($dadosUrl)){
                    $voltar .= "../";
                    $contador++;
                }
                echo "<script>javascript:window.location='".$voltar."todosdebates';</script>";
            break;
        }        
    }
?>
 