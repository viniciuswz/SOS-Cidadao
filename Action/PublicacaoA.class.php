<?php
namespace Action;
use Model\PublicacaoM;
use Core\Logradouro;
use Core\PublicacaoDenuncia;
use Core\Usuario;
use Classes\TratarImg;
use Classes\TratarDataHora;
use Classes\Paginacao;

class PublicacaoA extends PublicacaoM{
    
    private $sqlInsert = "INSERT INTO publicacao(texto_publi, img_publi, titulo_publi, cod_usu, cod_cate, cep_logra,dataHora_publi)
                            VALUES('%s','%s','%s','%s','%s','%s',now())";

    private $sqlSelect = "SELECT usuario.cod_usu,usuario.nome_usu, img_perfil_usu, img_publi,titulo_publi, cod_publi, 
                                    texto_publi, dataHora_publi, descri_cate,categoria.cod_cate, endere_logra, nome_bai, logradouro.cep_logra      
                                    FROM usuario INNER JOIN publicacao on (usuario.cod_usu = publicacao.cod_usu) 
                                    INNER JOIN categoria ON (publicacao.cod_cate = categoria.cod_cate)
                                    INNER JOIN logradouro ON (publicacao.cep_logra = logradouro.cep_logra) 
                                    INNER JOIN bairro ON (logradouro.cod_bai = bairro.cod_bai) 
                                    INNER JOIN tipo_usuario ON (usuario.cod_tipo_usu = tipo_usuario.cod_tipo_usu) 
                                    WHERE descri_tipo_usu = 'Comum' AND %s  %s %s";
    
    private $whereListFromALL = "status_publi = 'A'  AND status_usu = 'A' ";
    
    private $whereIdUser = " AND usuario.cod_usu = '%s' ";

    private $whereIdPubli = " AND publicacao.cod_publi = '%s'"; 

    private $sqlSelectQuantCurti = "SELECT COUNT(*) FROM publicacao_curtida WHERE cod_publi = '%s' AND status_publi_curti = 'A'";

    private $sqlSelectQuantComen = "SELECT COUNT(*) FROM comentario INNER JOIN usuario on(usuario.cod_usu = comentario.cod_usu)
                                        INNER JOIN tipo_usuario ON (usuario.cod_tipo_usu = tipo_usuario.cod_tipo_usu)  
                                        WHERE cod_publi = '%s' AND status_comen = 'A' AND descri_tipo_usu = 'Comum'
                                        AND status_usu = 'A'";//Nao conta com comentarios da prefeitura

    private $sqlSelectVerifyResPrefei = "SELECT COUNT(*) FROM comentario INNER JOIN usuario on(usuario.cod_usu = comentario.cod_usu)
                                        INNER JOIN tipo_usuario ON (usuario.cod_tipo_usu = tipo_usuario.cod_tipo_usu)  
                                        WHERE cod_publi = '%s' AND status_comen = 'A' AND (descri_tipo_usu = 'Prefeitura' or descri_tipo_usu = 'Funcionario')
                                        AND status_usu = 'A'";//Nao conta com comentarios da prefeitura

    private $sqlSelectVerifyCurti = "SELECT COUNT(*) FROM publicacao_curtida WHERE cod_publi = '%s' AND cod_usu = '%s' AND status_publi_curti = 'A'";
   
    private $sqlSelectQuantPubli = "SELECT COUNT(*) FROM publicacao INNER JOIN usuario ON (usuario.cod_usu = publicacao.cod_usu)  
                                        WHERE status_publi = 'A'  AND status_usu = 'A' %s";

    private $sqlQtdNRespon = "SELECT %s FROM publicacao     
                                INNER JOIN usuario on(usuario.cod_usu = publicacao.cod_usu)
                                INNER JOIN tipo_usuario ON (usuario.cod_tipo_usu = tipo_usuario.cod_tipo_usu)
                                WHERE
                                cod_publi  not in(SELECT cod_publi FROM comentario INNER JOIN usuario on(usuario.cod_usu = comentario.cod_usu)
                                INNER JOIN tipo_usuario ON (usuario.cod_tipo_usu = tipo_usuario.cod_tipo_usu) 
                                WHERE  status_comen = 'A' AND (descri_tipo_usu = 'Prefeitura' or descri_tipo_usu = 'Funcionario') AND status_usu = 'A')
                                AND %s %s";
    
    private $sqlUpdateStatusPubli = "UPDATE publicacao SET status_publi = '%s' WHERE cod_publi = '%s' AND cod_usu = '%s'";

    private $sqlPaginaAtual;
    

    public function cadastrarPublicacao($bairro, $local){
        $this->cadastrarLocal($bairro, $local);
        $this->tratarImagem();
        $sql = sprintf($this->sqlInsert, 
                        $this->getTextoPubli(),
                        $this->getImgPubli(),
                        $this->getTituloPubli(),
                        $this->getCodUsu(),
                        $this->getCodCate(),
                        $this->getCepLogra()
                    );
        $inserir = $this->runQuery($sql);
        if(!$inserir->rowCount()){  // Se der erro cai nesse if          
            throw new \Exception("Não foi possível realizar o cadastro da publicacao",9);   
        }
        return TRUE;

    }

    public function cadastrarLocal($bairro, $local){        
        $logradouto = new Logradouro();
        $logradouto->setEndereLogra($local);
        $logradouto->setCepLogra($this->getCepLogra());             
        $logradouto->selectCep($bairro);
    }

    public function tratarImagem(){ // Mexer depois nessa funcao
        //Fazer a parada da thumb
        if(empty($this->getImgPubli())){
            $this->setImgPubli(NULL);
            return;
        }
        $tratar = new TratarImg();
        $novoNome = $tratar->tratarImagem($this->getImgPubli(), 'publicacao');
        $this->setImgPubli($novoNome);
        return;
    } 

    public function ListFromALL($pagina = null){ // Listar todas as publicacoes
       
        $sqlPaginacao = $this->controlarPaginacao($this->sqlSelectQuantPubli,null,6,$pagina);
        $sql = sprintf($this->sqlSelect,
                        $this->whereListFromALL,                       
                       ' AND 1=1', //colocar um AND 1=1 pq nao tem mais nada, se nao colocar da pau
                       $sqlPaginacao
                       
        );        
        $res = $this->runSelect($sql);
        
        return $dadosTratados = $this->tratarInformacoes($res);
    }

    public function ListByIdUser($pagina = null){ //Listar publicacoes de um usuario    
        $prepararWhereUser = sprintf($this->whereIdUser, $this->getCodUsu()); 

        $sqlPaginacao = $this->controlarPaginacao($this->sqlSelectQuantPubli,$prepararWhereUser,6,$pagina);
        $sql = sprintf($this->sqlSelect,
                    $this->whereListFromALL,
                    $prepararWhereUser,
                    $sqlPaginacao
        );  
        $res = $this->runSelect($sql);        
        return $dadosTratados = $this->tratarInformacoes($res);
    }

    public function listByIdPubli($restricao = null){ // Listar pelo id da publicacao
        $usuario = new Usuario();
        $usuario->setCodUsu($this->getCodUsu());
        $tipoUsu = $usuario->getDescTipo();
        $sql = sprintf($this->sqlSelect,
                        $this->whereListFromALL,
                        ' %s',
                        ' %s '
        );  
        
        if($restricao == null OR ($tipoUsu == 'Adm' OR $tipoUsu == 'Moderador')){ // Nao precisa ser o dono da publicacao
            $prepararWherePubli = sprintf($this->whereIdPubli, $this->getCodPubli());
            $sql = sprintf(
                $sql,
                $prepararWherePubli,
                ' AND 1=1 '
            );
            $erro = "Não foi possível fazer o select"; // Se der erro aparece esta mensagem
        }else{ // Precisa ser o dono da publicacao
            $sql = sprintf(
                    $sql,
                    sprintf(
                        $this->whereIdUser,
                        $this->getCodUsu()
                    ),
                    sprintf(
                        $this->whereIdPubli,
                        $this->getCodPubli()
                    )
                );
                $erro = "Você nao tem permissao para esta publicacao";// Se der erro aparece esta mensagem
        }       
                         
        
        $res = $this->runSelect($sql);
        if(empty($res)){
            throw new \Exception($erro,9); 
        }        
        $dadosTratados = $this->tratarInformacoes($res);       
        $dadosTratados[0]['class_cate'] = $this->tirarAcentos($dadosTratados[0]['descri_cate']);//Tirar acentos pra entrar como classe no html
        //var_dump($dadosTratados);
        return $dadosTratados;
        
    }

    public function tratarInformacoes($dados){        
        $contador = 0;
               
        while($contador < count($dados)){//Nesse while so entra a parte q me interresa
            $texto = ""; //Limpar a variavel
            $dados[$contador]['dataHora_publi'] = $this->tratarHora($dados[$contador]['dataHora_publi']);//Calcular o tempo
            $cepComTraco = substr($dados[$contador]['cep_logra'],0,5) . '-' . substr($dados[$contador]['cep_logra'],5,8); //Colocar o - no cep 

            $texto .=  $dados[$contador]['nome_bai'] . ', ';   
            $texto .=  $dados[$contador]['endere_logra'];  
            $dados[$contador]['endereco_organizado_fechado'] = $texto; // Nesse campo fica o endereco sem o cep
            $texto .=  ', ';
            $texto .=  $cepComTraco; 
            //$texto = Endereço formatado
            $dados[$contador]['endereco_organizado_aberto'] = $texto; //Cria um novo campo na array, com o endereço organizado com o cep 

            $dados[$contador]['quantidade_curtidas'] =  $this->getQuantCurtidas($dados[$contador]['cod_publi']); //Pegar quantidade de curtidas
            $dados[$contador]['quantidade_comen'] =  $this->getQuantComen($dados[$contador]['cod_publi']); //Pegar quantidade de comentarios
            $dados[$contador]['indResPrefei'] =  $this->getVerifyResPrefei($dados[$contador]['cod_publi']); //Veficar resposta da prefeitura   
            if(!empty($this->getCodUsu())){//Só entar aqui se ele estiver logado
                $dados[$contador]['indCurtidaDoUser'] =  $this->getVerifyCurti($dados[$contador]['cod_publi']);//Verificar se ele curtiu a publicacao
                $dados[$contador]['indDenunPubli'] =  $this->getVerificarSeDenunciou($dados[$contador]['cod_publi']);//Verificar se ele denunciou a publicacao
                //Me retorna um bollenao
            }
            
            $contador++;
        }     
        
        return $dados;       
        
    }

    public function tirarAcentos($palavra){ // Tirar acentos de palavras
        $semAcento = strtolower(preg_replace( '/[`^~\'"]/', null, iconv( 'UTF-8', 'ASCII//TRANSLIT', $palavra )));       
        $tirarEspacos = str_replace(" ", "", $semAcento);
        return $tirarEspacos;        
    }
    
    public function tratarHora($hora){ 
        $tratarHoras = new TratarDataHora($hora);
        return $tratarHoras->calcularTempo('publicacao','N');
    }

    public function getQuantCurtidas($idPubli) { // Pegar quantidades de curtidas na publicacao
        $sql = sprintf($this->sqlSelectQuantCurti,
                                $idPubli);  
        $res = $this->runSelect($sql);         
        return $res[0]['COUNT(*)'];

    }

    public function getQuantComen($idPubli) { // Pegar quantidade de comentarios na publicacao
        $sql = sprintf($this->sqlSelectQuantComen,
                                $idPubli);          
        $res = $this->runSelect($sql);         
        return $res[0]['COUNT(*)'];
    }
    public function getVerifyResPrefei($idPubli) { // Pegar quantidade de resposta da prefeitura
        $sql = sprintf($this->sqlSelectVerifyResPrefei,
                                $idPubli);          
        $res = $this->runSelect($sql);         
        $quantidade = $res[0]['COUNT(*)'];
        if($quantidade > 0){ //Se for maior q zero é pq ta respondida
            return TRUE;
        }
        return FALSE;
    }

    public function getVerifyCurti($idPubli){ //Verificar se o usuario ja curtiu a publicacao
        $sql = sprintf($this->sqlSelectVerifyCurti,
                        $idPubli,
                        $this->getCodUsu());          
        $res = $this->runSelect($sql);
        $quantidade = $res[0]['COUNT(*)'];
        if($quantidade > 0){ //Se for maior q zero é pq ele curtiu
            return TRUE;
        }
        return FALSE;
    }    

    public function getVerificarSeDenunciou(){
        $idPubli = $this->getCodPubli();
        $idUser = $this->getCodUsu();

        $denun = new PublicacaoDenuncia();
        $denun->setCodPubli($idPubli);
        $denun->setCodUsu($idUser);
        return $denun->verificarSeDenunciou();
    }

    public function quantidadeTotalPubli($sqlCountPubli,$where){//Pegar a quantidade de publicacoes
        if($where != null){ // Se for passado o paramentro null, nao tem restriçoes retorna todas as publicacoes
            $sql = sprintf($sqlCountPubli,
                                $where); 
        }else{
            $sql = sprintf($sqlCountPubli,
                                'AND 1=1');
        }
          
        $res = $this->runSelect($sql);        
        return $res[0]['COUNT(*)'];
    }    

    public function getIdsPubliNRespo($pagina = null){ //Pegar os ids da publicacoes nao respondidas
        $sqlQtdNRespon = sprintf(
            $this->sqlQtdNRespon,
            'COUNT(*)',
            $this->whereListFromALL,
            'AND 1=1'
        ); // Preparar o sql da quantiade de publicacões

        $sqlPaginacao = $this->controlarPaginacao($sqlQtdNRespon, null, 6, $pagina);

        $sql = sprintf(
            $this->sqlQtdNRespon,
            'cod_publi',
            $this->whereListFromALL,
            $sqlPaginacao
        );

        $res = $this->runSelect($sql); // Ids Na array;  
        $contador = 0;
        $ids = "";
        while($contador < count($res)){ // Nao quero q retorne uma array, mas sim umas string, q contenha os ids
            if(($contador + 1) == count($res)){                                
                $ids .= $res[$contador]['cod_publi'];
            }else{
                $ids .= $res[$contador]['cod_publi'] . ', ';
            }
            $contador++;
        }
        $ids = " publicacao.cod_publi in(".$ids.")"; // WHERE apenas os ids q eu quero
        return $ids;
              
    }

    public function getPubliNRespo($pagina = null){//Pegar os dados das publicacoes nao respondidas
        $sql = sprintf(
                $this->sqlSelect,
                $this->getIdsPubliNRespo($pagina),
                ' AND 1=1',
                ' AND 1=1'
        );
        $res = $this->runSelect($sql); // Ids Na array;
        return $dadosTratados = $this->tratarInformacoes($res);
    }
    
    public function controlarPaginacao($sqlCountPubli, $where, $quantidadePubliPagina, $pagina = null){ // Fazer o controle da paginacao       
        $paginacao = new Paginacao(); 
        $paginacao->setQtdPubliPaginas($quantidadePubliPagina); // Setar a quantidade de publicacoes por pagina
        
        $quantidadeTotalPubli = $this->quantidadeTotalPubli($sqlCountPubli,$where);   //Pega a quantidade de publicacoes no total          
        
        $sqlPaginacao = $paginacao->prapararSql('dataHora_publi','desc', $pagina, $quantidadeTotalPubli);//Prepare o sql
        $this->setQuantidadePaginas($paginacao->getQuantidadePaginas());//Seta a quantidade de paginas no total
        $this->setPaginaAtual($paginacao->getPaginaAtual());
        return $sqlPaginacao;
        
    }   
    
    //////////////////////////////////////////////////////////////////////////////////////////////////////
    // UPDATESSSSSS

    public function updateStatusPubli($status){        
        $usuario = new Usuario();
        $usuario->setCodUsu($this->getCodUsu());
        $tipo = $usuario->getDescTipo();       

        if($tipo == 'Adm' or $tipo == 'Moderador'){
            $sqlUpdatePubli = "UPDATE publicacao SET status_publi = '%s' WHERE cod_publi = '%s'";
            $sql = sprintf(
                $sqlUpdatePubli,
                $status,
                $this->getCodPubli()                
            );
        }else{
            $sql = sprintf(
                $this->sqlUpdateStatusPubli,
                $status,
                $this->getCodPubli(),
                $this->getCodUsu()
            );
        }    
        $resposta = $this->runQuery($sql);
        if(!$resposta->rowCount()){
            throw new \Exception("Não foi possível mudar o status",9);
        }

        return;
    }
   
}