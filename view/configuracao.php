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
                        require_once('opcoes.php');                       
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
                <section class="perfil-base">
                        <h3>Configurações da conta</h3>
                        <div class="perfil" id="config">
                            
                                <div>
                                        <span>usuário des de 15 de Dezembro de 2015</span>
                                        
                                        <div>
                                            <img src="imagens/perfil.jpg">
                                        </div>
                                    </div>
                                </div>
                       
                    </section>
            <nav class="menu-perfil">
                <ul class="espacos">
                    <li class="ativo"><a href="configuracao.html">pessoais</a></li>

            <li><a href="configuracoes2.html">Segurança</a></li>

                    
                    
                </ul>
            </nav>
            <section class="form-config">
                <form>
                    <div class="campo-texto-config">
                            <label for="user">Nome</label>
                            <input type="text" name="user" id="user" placeholder="Nome" autocomplete ="off">
                        </div>
                        <div class="campo-texto-config">
                            <label for="email">E-mail</i></label>
                            <input type="email" name="email" id="email" placeholder="E-mail">
                    </div>
                    <button type="submit">Alterar</button>
            </form>
        </section>

        </div>
    </body>
</html>
