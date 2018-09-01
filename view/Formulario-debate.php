<?php
session_start();
    require_once('../Config/Config.php');
    require_once(SITE_ROOT.DS.'autoload.php');
        
    use Core\Usuario;
    try{
        $tipoUsuPermi = array('Comum');        
        Usuario::verificarLogin(1,$tipoUsuPermi);  // 1 = tem q estar logado
        
        $dados = new Usuario();
        $dados->setCodUsu($_SESSION['id_user']);
        $resultado = $dados->getDadosUser();
           
           
?>
<!DOCTYPE html>
<html lang=pt-br>
    <head>
        <title>S.O.S Cidadão</title>

        <meta charset=UTF-8> <!-- ISO-8859-1 -->
        <meta name=viewport content="width=device-width, initial-scale=1.0">
        <meta name=description content="Site de reclamações para a cidade de Barueri">
        <meta name=keywords content="Reclamação, Barueri"> <!-- Opcional -->
        <meta name=author content='equipe 4 INI3A'>

        <!-- favicon, arquivo de imagem podendo ser 8x8 - 16x16 - 32x32px com extensão .ico -->
        <link rel="shortcut icon" href="imagens/favicon.ico" type="image/x-icon">

        <!-- CSS PADRÃO -->
        <link href="css/default.css" rel=stylesheet>

        <!-- Telas Responsivas -->
        <link rel=stylesheet media="screen and (max-width:480px)" href="css/style480.css">
        <link rel=stylesheet media="screen and (min-width:481px) and (max-width:768px)" href="css/style768.css">
        <link rel=stylesheet media="screen and (min-width:769px) and (max-width:1024px)" href="css/style1024.css">
        <link rel=stylesheet media="screen and (min-width:1025px)" href="css/style1025.css">

        <!-- JS-->

        <script src="lib/_jquery/jquery.js"></script>
        
        <script src="js/js.js"></script>
        <script src="../teste.js"></script>

    </head>
    <body>
        <header>
            <img src="imagens/Ativo2.png" alt="logo">
            <form>
                <input type="text" name="pesquisa" id="pesquisa" placeholder="Pesquisar">
                <button type="submit"><i class="icone-pesquisa"></i></button>
            </form>
            <nav class="menu">
                <ul>
                    <li><nav class="notificacoes">
                        <h3>notificações<span id="not-fechado"></span></h3>
                        <ul id="menu23">
                            
                            <li>
                        </ul>
                    </nav><a href="#" id="abrir-not"><i class="icone-notificacao" id="noti"></i>Notificações</a></li>
                    <li><a href="todasreclamacoes.php"><i class="icone-reclamacao"></i>Reclamações</a></li>
                    <li><a href="todosdebates.php"><i class="icone-debate"></i>Debates</a></li>
                </ul>
            </nav>            
            <?php
                if(!isset($resultado)){
                    echo '<a href="login.php"><i class="icone-user" id="abrir"></i></a>';
                }else{
                    echo '<i class="icone-user" id="abrir"></i>';
                }
            ?>
            
        </header>
        <?php
                if(isset($resultado)){   
        ?>
        <div class="user-menu">
           
            <a href="javascript:void(0)" class="fechar">&times;</a>            
            <div class="mini-perfil">
                <div>    
                    <img src="../Img/perfil/<?php echo $resultado[0]['img_perfil_usu'] ?>" alt="perfil">
                </div>    
                    <img src="../Img/capa/<?php echo $resultado[0]['img_capa_usu'] ?>" alt="capa">
                    <p><?php echo $resultado[0]['nome_usu'] ?></p>
            </div>
           
            <nav>
                <ul>
                    <li><a href="perfil.html"><i class="icone-user"></i>Meu perfil</a></li>
                    <li><a href="#"><i class="icone-salvar"></i>Salvos</a></li>
                    <hr>
                    <?php
                        if($resultado[0]['descri_tipo_usu'] == 'Adm' OR $resultado[0]['descri_tipo_usu'] == 'Moderador'){
                            echo '<li><a href="#"><i class="icone-adm"></i>Area de administrador</a></li>';
                            echo '<hr>';
                        }else if($resultado[0]['descri_tipo_usu'] == 'Funcionario' OR $resultado[0]['descri_tipo_usu'] =='Prefeitura'){
                            echo '<li><a href="#"><i class="icone-salvar"></i>Area da prefeitura </a></li>';
                            echo '<hr>';
                        }                        
                    ?>                     
                    <li><a href="#"><i class="icone-config"></i>Configurações</a></li>
                    <li><a href="../sair.php"><i class="icone-logout"></i>Log out</a></li>

                </ul>
            </nav>
            
        </div>       
        <?php
            }
        ?>

        <div id="container">

   
            <form class="formulario" name="envio debate" method="post" action="../EnviarDebate.php" enctype="multipart/form-data">
                <!--FORMULARIO ENVIO TITULO E TEMA-->
                <div class="informacoes">
                    <h3>Informações importantes</h3>
                    <hr>
                        <div class="campo-envio">
                            <label for="titulo">Título<p></p></label>
                            <input type="text" id="titulo" name="titulo" placeholder="fora temer"  maxlength="20" autocomplete="off">
                            <span></span>
                
                    </div>
                        
                        <div class="campo-envio">
                            <label for="tema">Tema<p></p></label>
                            <input type="text" id="tema" name="tema" placeholder="algum tema ai"  maxlength="20" autocomplete="off">
                            <span></span>
                
                        </div>
                    </div>
                            
                    <!--FORMULARIO ENVIO DA FOTO-->           
                    <div class="imagem">
                        <h3>Escolha uma foto para o debate</h3>
                        <hr>
                        <div class="envio-img">
                                            
                            <input type="file" name="imagem" id="imagemDebateInput" accept="image/png, image/jpeg">
                            <label for="imagemDebateInput"><p><i class="icone-camera"></i>Escolha foto</p>
                                
                        <div>
                            <img src="imagens/capa.png" id="imgPreview">
                        </div>
                            </label>
                        </div>
                        <p></p>
                    </div>
                                
                    <!--FORMULARIO DESCRIÇÃO DO DEBATE--> 
                        <div class="campo-texto"> 
                            <h3>sobre o que vai debater ?</h3>
                        
                        <hr>
                    
                        
                            <textarea placeholder="escreva aqui" id="sobre" name="descricao"></textarea>
                                <p></p>
                                <input type="submit" value="iniciar debate">
                    
                        </div>     
            </form>

 

        </div>
    </body>
</html>
<?php
    }catch (Exception $exc){
        $erro = $exc->getCode();   
        $mensagem = $exc->getMessage();  
        switch($erro){
            case 2://Nao esta logado    
                echo "<script> alert('$mensagem');javascript:window.location='./loginTemplate.php';</script>";
                break;
            case 6://Não é usuario comum                 
                echo "<script> alert('$mensagem');javascript:window.location='./starter.php';</script>";
                break;            
        }        
    }
?>