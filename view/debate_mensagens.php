<?php
    if(!file_exists('Config/Config.php')){        
        echo "<script>javascript:window.location='todasreclamacoes';</script>";       
    }
    session_start();
    require_once('Config/Config.php');
    require_once(SITE_ROOT.DS.'autoload.php');    
    
    use Core\Usuario;    
    use Core\Debate;
    use Core\Mensagens;
    use Classes\ValidarCampos;
    try{        

        $tipoUsuPermi = array('Prefeitura','Adm','Funcionario','Moderador','Comum');
        Usuario::verificarLogin(1,$tipoUsuPermi);

        $dadosUrl = explode('/', $_GET['url']);

        if(count($dadosUrl) > 2){ // injetou parametros
            throw new \Exception('Não foi possível achar o debate',45);
        }else if(!isset($dadosUrl[1])){ // não digitou o id
            throw new \Exception('Não foi possível achar o debate',9);
        }else{
            $numVoltar = count($dadosUrl) - 1;
            $_GET['ID'] = $dadosUrl[1];
        }
        $voltar = '../';
        $debate = new Debate();           

        if(isset($_SESSION['id_user']) AND !empty($_SESSION['id_user'])){
            $debate->setCodUsu($_SESSION['id_user']); 
            $tipoUsu = $_SESSION['tipo_usu'];      
        }

        


        $nomesCampos = array('ID');// Nomes dos campos que receberei da URL    
        $validar = new ValidarCampos($nomesCampos, $_GET);
        $validar->verificarTipoInt($nomesCampos, $_GET); // Verificar se o parametro da url é um numero     
        $debate->setCodDeba($_GET['ID']); 
                
        $resposta = $debate->listByIdDeba('sqlListDebaQuandoAberto');
        $debate->verificarSeParticipaOuNao($_GET['ID'], TRUE);       

        $participantes = $debate->listarParticipantes(' usuario.cod_usu, nome_usu, img_perfil_usu, ind_visu_criador ');
          
        

?>
<!DOCTYPE html>
<html lang=pt-br>
    <head>
        <title>Suas Conversas</title>

        <meta charset=UTF-8> <!-- ISO-8859-1 -->
        <meta name=viewport content="width=device-width, initial-scale=1.0">
        <meta name=description content="Todos os debates que você participa em uma só pagina.">
        <meta name=keywords content="Reclamação, Barueri"> <!-- Opcional -->
        <meta name=author content='equipe 4 INI3A'>
        <meta name="theme-color" content="#089E8E" />

        <!-- favicon, arquivo de imagem podendo ser 8x8 - 16x16 - 32x32px com extensão .ico -->
        <link rel="shortcut icon" href="<?php echo $voltar ?>view/imagens/favicon.ico" type="image/x-icon">

        <!-- CSS PADRÃO -->
        <link href="<?php echo $voltar ?>view/css/default.css" rel=stylesheet>

        <!-- Telas Responsivas -->
        <link rel=stylesheet media="screen and (max-width:480px)" href="<?php echo $voltar ?>view/css/style480.css">
        <link rel=stylesheet media="screen and (min-width:481px) and (max-width:768px)" href="<?php echo $voltar ?>view/css/style768.css">
        <link rel=stylesheet media="screen and (min-width:769px) and (max-width:1024px)" href="<?php echo $voltar ?>view/css/style1024.css">
        <link rel=stylesheet media="screen and (min-width:1025px)" href="<?php echo $voltar ?>view/css/style1025.css">
        

        <!-- JS-->

        <script src="<?php echo $voltar ?>view/lib/_jquery/jquery.js"></script>
        <script src="<?php echo $voltar ?>view/js/js.js"></script>
        <script src="<?php echo $voltar ?>view/js/rolagemChat.js"></script>
        <script src="<?php echo $voltar ?>TesteMensagem.js"></script>
        

    </head>
    <body>
        

        <div id="container">
        <input type="hidden" id="IdDeba" value = "<?php echo $_GET['ID']?>">
        <section class="debate-full">
               <?php
                    if(!empty($_SESSION['atu'])){ // mensagem de bem-vindo
                        if($_SESSION['atu'] == '1'){
                            echo '<script>alerta("Certo","Bem-vindo a discussão")</script>';
                        }else if($_SESSION['atu'] == '2'){
                            echo '<script>alerta("Certo","Usuário Removido")</script>';
                        }                        
                        unset($_SESSION['atu']);
                    }
               ?>
          
                <aside class="usuarios-debate-info">
                        <span id="fechar-debate-info">&times;</span>
                        <div class="moldura-debate">
                            <div>
                                <div id="ImgDeba">
                                    
                                </div>
                                <?php 
                                    if($resposta[0]['cod_usu'] == $_SESSION['id_user']){
                                ?>                                
                                   
                                <?php 
                                    }
                                ?> 
                                
                            </div>
                            
                        </div>
                        <div class="infos">
                            <h3><?php echo $resposta[0]['nome_deba']?></h3>
                            <p>Criado por <span><?php echo $resposta[0]['nome_usu']?></span></p>
                        </div>
                        <div class="participantes">
                            <h4>Participantes (<?php echo $resposta[0]['qtdParticipantes']?>)</h4>
                            <?php
                                $contador = 0;
                                while($contador < count($participantes)){
                            ?>  
                            <div class="item-participante" <?php if($participantes[$contador]['ind_visu_criador'] == 'I'){echo 'style="order:1"';}else{echo 'style="order:2"';}?> >
                                <div class="img-participante">
                                    <img src="<?php echo $voltar ?>Img/perfil/<?php echo $participantes[$contador]['img_perfil_usu'] ?>">
                                </div>
                                
                                <p><?php echo $participantes[$contador]['nome_usu'] ?></p>
                                <?php
                                    if($participantes[$contador]['ind_visu_criador'] == 'I'){
                                        echo '<span>DONO</span>';
                                    }
                                ?>                                
                                        <div class="mini-menu-item sem-btn">                                    
                                            <div>
                                                <ul>
                                                    <?php 
                                                        if(isset($_SESSION['id_user']) AND $_SESSION['id_user'] == $resposta[0]['cod_usu'] AND $_SESSION['id_user'] != $participantes[$contador]['cod_usu']
                                                            OR ($tipoUsu == 'Adm' OR $tipoUsu == 'Moderador')){ // parei aqui link pra apagar
                                                            if(($tipoUsu == 'Adm' OR $tipoUsu == 'Moderador') AND $participantes[$contador]['cod_usu'] != $resposta[0]['cod_usu']){ // nao aparece a opcao remover participante quando que estiver logo for adm e o usario participante o dono
                                                                echo '<li><span><a href="../SairDebate.php?ID='. $resposta[0]['cod_deba'].'&IDUsu='.$participantes[$contador]['cod_usu'].'">Remover Usuario</a></span></li>';
                                                            }else if($tipoUsu == 'Comum'){
                                                                echo '<li><span><a href="../SairDebate.php?ID='. $resposta[0]['cod_deba'].'&IDUsu='.$participantes[$contador]['cod_usu'].'">Remover Usuario</a></span></li>';
                                                            }                                                            
                                                            echo "<li><span><a href='../perfil_reclamacao/". $participantes[$contador]['cod_usu']."'>Perfil</a></span></li>";
                                                        }else if(isset($_SESSION['id_user']) AND $_SESSION['id_user'] != $participantes[$contador]['cod_usu']){
                                                            echo "<li><span><a href='../perfil_reclamacao/". $participantes[$contador]['cod_usu']."'>Perfil</a></span></li>";
                                                        }                                                
                                                    ?>                                                                                                        
                                                </ul>
                                            </div>
                                        </div>                                   
                            </div>
                            <?php                                    
                                    $contador++;
                                }
                            ?> 
                        
                </aside>
                <?php
                        if($tipoUsu == 'Comum'){
                ?>
                                    <aside class="contatos">
                                        <header class="">
                                                <h3>Suas conversas</h3>
                                                <p id="fechar-contatos">&times;</p>
                                        </header>
                                            <nav id="contatosJs">                                               
                                            </nav>
                                    </aside>
                <?php
                        }
                ?>
                
        
           <div class="batepapo">
                <header>
                    <a href="../todosdebates" style="order:1">&#x21FD;</a>
                    <div class="ft_debate-mensagem" style="order:2">
                        <img src="<?php echo $voltar ?>Img/debate/<?php echo $resposta[0]['img_deba']?>" alt="debate">
                    </div>
                    <h4 style="order:3"><?php echo $resposta[0]['nome_deba']?></h4>
                    <div class="mini-menu-item" style="order:4">
                            <i class="icone-3pontos"></i>
                            <div>
                                <ul>
                                    <li id="abrir-debate-info"><span>Dados do grupo</span></li>
                                    <?php
                                        if(isset($_SESSION['id_user']) AND $_SESSION['id_user'] == $resposta[0]['cod_usu']){
                                            echo '<li><a href="../SairDebate.php?ID='. $resposta[0]['cod_deba'].'"><span>Apagar grupo</span></a></li>';
                                        }else if($_SESSION['tipo_usu'] == 'Comum'){
                                            echo '<li><a href="../SairDebate.php?ID='. $resposta[0]['cod_deba'].'"><span>Sair do grupo</span></a></li>';
                                        }else if($_SESSION['tipo_usu'] == 'Adm' OR $_SESSION['tipo_usu'] == 'Moderador'){
                                            echo '<li><a href="../SairDebate.php?ID='. $resposta[0]['cod_deba'].'"><span>Apagar Grupo</span></a></li>';
                                        }
                                    ?>
                                    <li id="abrir-contatos"><span>Suas conversas</span></li>
                                </ul>
                            </div>
                    </div>
                </header>
                <div id="pa" class="mensagens">
                <div class="fundo-zipzop"></div>
                <div class="lds-ring" style="margin: 0 auto;display: none;width: 50px;position:fixed;top:60px;left: 50%;transform: translateX(-50%);" id="loader"><div></div><div></div><div></div><div></div></div>

                        
                </div>                     
               <form method="post" id="formDebaMen">
                    <?php
                        if($tipoUsu == 'Comum'){
                    ?>
                        <input type="hidden" name="ID" value="<?php echo $_GET['ID'] ?>" />                        
                        <input type="text" name="texto" id="texto" placeholder="digite aqui..." autocomplete="off"><button id="btn-debate" type="submit" disabled> ></button> 
                   <?php
                        }
                    ?>
               </form>      
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
                echo "<script>javascript:window.location='../todosdebates';</script>";
                break;
            case 9://Não foi possivel achar a publicacao  
                echo "<script> alert('$mensagem');javascript:window.location='todosdebates';</script>";
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
            default: //Qualquer outro erro cai aqui
                echo "<script> alert('$mensagem');javascript:window.location='../todosdebates';</script>";
        }   
    }  
?> 

 
