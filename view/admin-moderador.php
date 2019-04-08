<?php
    if(!file_exists('Config/Config.php')){        
        echo "<script>javascript:window.location='todasreclamacoes';</script>";       
    }
    session_start();
    require_once('Config/Config.php');
    require_once(SITE_ROOT.DS.'autoload.php');
    
    use Core\Usuario;
    
    try{

        $tipoUsuPermi = array('Adm');
        Usuario::verificarLogin(1,$tipoUsuPermi);  // Tem q estar logado    
        
        $dadosUrl = explode('/', $_GET['url']);
        if(count($dadosUrl) > 1){ // injetou parametros
            throw new \Exception('Não foi possível achar o debate',45);
        }
        
        $usu = new Usuario();
        $indUsuPref = $usu->verifyExistContPrefei();        
        if($indUsuPref){
            $inputPref = '<input type="radio" value="Prefeitura" name="tipo" id="prefeitura-input" disabled> 
                            <label for="prefeitura-input">prefeitura</label>';
        }else{
            $inputPref = '<input type="radio" value="Prefeitura" name="tipo" id="prefeitura-input">
                            <label for="prefeitura-input">prefeitura</label>';
        }
        $usu->setCodUsu($_SESSION['id_user']);
        $resultado = $usu->getDadosUser(true);
        $tipos = array();
        
        if(!isset($_GET['tipo']) AND empty($_GET['tipo'])){
            $tipos[] = 'moderador';
            $parametro = 'moderador';
        }else{           
            $parametro = "";
            $contador = 1;
            foreach($_GET as $chaves => $valores){    
                if($chaves == 'tipo'){
                    foreach($valores as $chave => $valor){
                        $tipos[] = $valor;
                        if($contador < count($_GET)){
                            $parametro .= 'tipo[]=';
                            $parametro .= $valor.'&';
                        }else{                            
                            $parametro .= 'tipo[]=';
                            $parametro .= $valor;
                        }
                        $parametro .= '&';
                        $contador++;  
                    }
                }
                
            }
        }
        isset($_GET['pagina']) ?: $_GET['pagina'] = null;  
        $res = $usu->getDadosUsuByTipoUsu($tipos,$_GET['pagina']);        
        $quantidadePaginas = $usu->getQuantidadePaginas();
        $pagina = $usu->getPaginaAtual();
        if(empty($res)){
            echo 'Não há nenhuma denuncia para verificar<br>';
        }
       
?>

<!DOCTYPE html>
<html lang=pt-br>
    <head>
        <title>Área de administrador</title>

        <meta charset=UTF-8> <!-- ISO-8859-1 -->
        <meta name=viewport content="width=device-width, initial-scale=1.0">
        <meta name=description content="Área restrita a usuários autorizados">
      
        <meta name=author content='equipe 4 INI3A'>

        <!-- favicon, arquivo de imagem podendo ser 8x8 - 16x16 - 32x32px com extensão .ico -->
        <link rel="shortcut icon" href="view/imagens/favicon.ico" type="image/x-icon">

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
        <script src="view/js/js.js"></script>
        <script src="teste.js"></script>

    </head>
    <body class="sempre-branco">
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

                <div class="filtro-admin ">
                    <i class="icone-filtro "></i>
                    <form id="filtroUser">
                    <span id="fechar-filtro">×</span>
                        <h3>Tipo de usuário</h3>
                        <div>    
                        
                            <label class="container"> Moderador 
                                <?php if(isset($_GET['tipo']) AND in_array('Moderador',$_GET['tipo'])) { ?>
                                    <input type="checkbox" checked="checked" name="tipo[]" value="Moderador">
                                <?php }else{ ?>
                                    <input type="checkbox" name="tipo[]"  value="Moderador">
                                <?php } ?>
                                <span class="checkmark"></span>
                            </label>
                                
                            <label class="container"> Prefeitura 
                            <?php if(isset($_GET['tipo']) AND in_array('Prefeitura',$_GET['tipo'])) { ?>
                                <input type="checkbox" checked="checked" name="tipo[]" value="Prefeitura">
                            <?php }else{ ?>
                                <input type="checkbox" name="tipo[]" value="Prefeitura">
                            <?php } ?>
                                <span class="checkmark"></span>
                            </label>
                               
                                
                        </div>
                                <input type="submit" class="botao-filtro" value="Filtrar">
                                
                    </form>
                </div>

            <div class="tabelinha-admin" >
                    <table>
                            <tr>
                              <th>Usuário</th>
                              <th>Tipo</th>
                              <th>Data</th>
                            </tr>
                            <tr>
                            <td  colspan="3" class="cad-adm"><div>+</div><p>Cadastrar</p></td>
                            </tr> 
                            <?php
                                $contador = 0;
                                $contador2 = 0;
                                while($contador < count($res)){
                                    echo '<tr class="tabelinha-linha">';  
                                        echo '<td>
                                            <div class="mini-menu-adm">
                                                <ul>';
                                                    if($res[$contador]['descri_tipo_usu'] != 'Prefeitura'){
                                                        echo '<li><a href="../ApagarUsuario.php?ID='.$res[$contador]['cod_usu'].'" class="remover-usuario">Remover '.$res[$contador]['descri_tipo_usu'].'</a></li>';
                                                    }else{
                                                        echo '<li><a>Não é permitido remover</a></li>';                                            
                                                    }
                                                    
                                                echo '</ul>                                        
                                            </div>  
                                        '.$res[$contador]['nome_usu'].'</td>';
                                        echo '<td>'.$res[$contador]['descri_tipo_usu'].'</td>';
                                        echo '<td>'.$res[$contador]['dataHora_cadastro_usu'].'</td>';                        
                                        //echo '<td>'.$res[$contador]['LinkApagarUsu'].'</td>'; 
                                    echo '</tr>';
                                    $contador++;
                                    $contador2 = 0;
                                }
                            ?>                            
                          </table>
                    </div>      
        </div>

        <div class="modal-adicionar-user">
            <div class="modal-adicionar-user-fundo"></div>
            <div class="box-adicionar-user">
                <div>
                    <h1>Adicionar usuário</h1>
                    <span class="fechar-adicionar-user">&times;</span>
                </div>
               
                <form id="add-user-form" method="POST">

                    <div class="campo-texto-icone"  >
                        <label for="user" ><i class="icone-user"></i></label>
                        <input type="text" name="nome" id="user" placeholder="Nome">
                    </div>

                    <div class="campo-texto-icone"  >
                        <label for="email" ><i class="icone-mail"></i></label>
                        <input type="email" name="email" id="email" placeholder="E-mail">
                    </div>

                    <div class="campo-texto-icone">
                        <label for="senha"><i class="icone-senha"></i></label>
                        <input type="password" name="senha" id="senha" placeholder="Senha">
                    </div>

                    <div class="opcoes-user">
                        <div>
                            
                            <input type="radio" value='Moderador' name="tipo" id="moderador-input" checked>
                            <label for="moderador-input">moderador</label>
                        </div>
                        <div>                                                        
                           <?php echo $inputPref ?>
                        </div>
                        
                    </div>

                    <div class="aviso-form-inicial">
                        <p>O campo tal e pa</p>
                    </div>

                    <button type="submit">Cadastrar</button>
                </form>
                
            </div>
        </div>
        <ul class="paginacao">
            <?php
                if($quantidadePaginas != 1){
                    $contador = 1;
                    while($contador <= $quantidadePaginas){
                        if(isset($pagina) AND $pagina == $contador){
                            echo '<li class="jaca"><a href="admin-moderador?'.$parametro.'&pagina='.$contador.'">'.$contador.'</a></li>'  ;  
                        }else{
                            echo '<li><a href="admin-moderador?'.$parametro.'&pagina='.$contador.'">'.$contador.'</a></li>'  ;
                        }                    
                        $contador++;        
                    }
                }            
            ?>
        </ul>
</body>
<?php
        
    }catch (Exception $exc){
        $erro = $exc->getCode();   
        $mensagem = $exc->getMessage();  
        switch($erro){
            case 2://Nao esta logado    
                echo "<script>javascript:window.location='../login';</script>";
                break;
            case 6://Não é usuario prefeitura ou func  
                echo "<script>javascript:window.location='../todasreclamacoes';</script>";
                break; 
            case 9://Não foi possivel achar a publicacao  
                echo "<script> alert('$mensagem');javascript:window.location='../todasreclamacoes';</script>";
                break; 
            case 45://Digitou um numero maior de parametros 
                unset($dadosUrl[0]);
                $contador = 1;
                $voltar = "";
                while($contador <= count($dadosUrl)){
                    $voltar .= "../";
                    $contador++;
                }
                 echo "<script> alert('$mensagem');javascript:window.location='".$voltar."todasreclamacoes';</script>";
            default: //Qualquer outro erro cai aqui
                echo "<script> alert('$mensagem');javascript:window.location='../todasreclamacoes';</script>";
        }   
    }
    
?>