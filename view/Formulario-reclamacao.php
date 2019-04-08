<?php
    if(!file_exists('Config/Config.php')){        
        echo "<script>javascript:window.location='todasreclamacoes';</script>";       
    }
    session_start();
    require_once('Config/Config.php');
    require_once(SITE_ROOT.DS.'autoload.php');
    
    use Core\Usuario;
    use Core\Categoria;
    try{       
        $tipoUsuPermi = array('Comum');
        Usuario::verificarLogin(1,$tipoUsuPermi);
        $cate = new Categoria();
        $categorias = $cate->gerarOptions();     
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
        <title>Reclame da Sua Cidade, Envie uma agora!</title>

        <meta charset=UTF-8> <!-- ISO-8859-1 -->
        <meta name=viewport content="width=device-width, initial-scale=1.0">
        <meta name=description content="Aqui é possivel enviar reclamações, é fácil e rápido! entre agora envie a sua, nossa cidade precisa disso para crescer.">
        <meta name=keywords content="Enviar, publicação, Barueri, Reclamação, Opinião"> <!-- Opcional -->
        <meta name=author content='equipe 4 INI3A'>

        <!-- favicon, arquivo de imagem podendo ser 8x8 - 16x16 - 32x32px com extensão .ico -->
        <link rel="shortcut icon" href="view/imagens/favicon.ico" type="view/image/x-icon">

        <!-- CSS PADRÃO -->
        <link href="view/css/default.css" rel=stylesheet>

        <!-- Telas Responsivas -->
        <link rel=stylesheet media="screen and (max-width:480px)" href="view/css/style480.css">
        <link rel=stylesheet media="screen and (min-width:481px) and (max-width:768px)" href="view/css/style768.css">
        <link rel=stylesheet media="screen and (min-width:769px) and (max-width:1024px)" href="view/css/style1024.css">
        <link rel=stylesheet media="screen and (min-width:1025px)" href="view/css/style1025.css">
        <meta name="theme-color" content="#089E8E" />

        <!-- JS-->

        <script src="view/lib/_jquery/jquery.js"></script>
        <script src="view/lib/_jquery/jquery.mask177.min.js"></script>
        <script src="view/js/js.js"></script>
        <script src="teste.js"></script>

        <script>
            $(document).ready(function(){
                $("#cep").mask("99999-999");
            });
        </script>
        <!-- cropp-->

        <link rel="stylesheet" href="view/lib/_croppie-master/croppie.css">
        <script src="view/lib/_croppie-master/croppie.js"></script>
        <script src="view/lib/_croppie-master/exif.js"></script>
    </head>
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

            
            <form class="formulario reclamaForm" action="enviarPublicacao.php" method="POST" enctype="multipart/form-data">
            <!--FORMULARIO ENVIO TITULO E TEMA-->
                <div class="informacoes">
                    <h3>Informações importantes</h3>
                    <hr>
                        <div class="campo-envio">
                            <label for="titulo">Título<p></p></label>
                            <input type="text" id="titulo" name="titulo" placeholder="ex. Árvore caída" autocomplete="off">
                            <span></span>
                
                    </div>

                    <div class="campo-envio">
                            <label for="cep">CEP<p></p></label>
                            <input type="text" id="cep" name="cep" placeholder="00000-000" >
                            <span></span>
                        </div>

                        <div class="campo-envio">
                            <label for="local">local<p></p></label>
                            <input type="text" id="input-disabled-local" placeholder="Rua, Avenida..." autocomplete="off" disabled >
                            <input type="hidden" id="local" name="local" autocomplete="off" >
                            <span></span>
                        </div>

                        <div class="campo-envio">
                                <label for="bairro">Bairro<p></p></label>
                                <input type="text" id="input-disabled-bairro" placeholder="Parque dos Churros" autocomplete="off" disabled>
                                <input type="hidden" id="bairro" name="bairro" autocomplete="off" >
                                <span></span>
                            </div>
                </div>
                        
            <!--FORMULARIO ENVIO DA FOTO-->           
                <div class="imagem">
                            <h3>Escolha uma foto para a publicação</h3>
                            <hr>
                        <div class="envio-img">
                            
                             <input type="file" accept="image/*" id="fotoReclamacao">
                            <label id="colocar-foto-reclamacao"><p><i class="icone-camera"></i>Escolha foto</p>
                            <input type="hidden" id="base64" name="base64" value="banana">
                        
                        <div>
                            <img src="" id="imgPreview">
                        </div>
                    </label>
                    
                </div>
                <p></p>
                </div>
            <!-- FORMULARIO CATEGORIAS-->
                <div class="categorias">
                    <h3 id="bacon">Sobre o que vai reclamar?</h3>
                    <hr>
                  
                    <div>
                            <?php
                                foreach($categorias as $valor){
                                    echo $valor;
                                }
                            ?>                       
                    </div>
                    <p></p>
                </div>
            <!--FORMULARIO DESCRIÇÃO DO DEBATE--> 
                <div class="campo-texto"> 
                    <!-- <h3>sobre o que vai debater ?</h3> -->
                    <!-- <hr> -->
                    <textarea placeholder="Escreva aqui" id="sobre" name="texto"></textarea>
                    <p></p>
                    <input type="submit" value="Enviar publicação" class="cta">
                </div>     
            </form>


        </div>
        <div class="modal-troca-foto-reclamacao">
            <div class="modal-troca-foto-reclamacao-fundo"></div>
            <div class="box-troca-foto-reclamacao">
                <div class="modal-titulo">
                    <h1>escolha uma foto para a publicação</h1>
                    <span class="fechar-troca-foto-reclamacao">&times;</span>
                </div>
                <div class="img-reclamacao-corta">
                   <img src="">
                </div>
                <div>  
                <div class="aviso-form-inicial ">
                        <p>O campo tal e pa</p>
                    </div>               
                   
                        <label for="fotoReclamacao"><p>Escolher foto</p></label>
                    
                    <button id="cortarReclamacao">Cortar foto</button>
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
                echo "<script>javascript:window.location='".$voltar."todasreclamacoes';</script>";
            break;
        }            
    }
?>