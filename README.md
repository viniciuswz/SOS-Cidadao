# S.O.S Cidadão

> **Observação:**  
> No começo, a equipe e Deus sabiam do projeto. Hoje, só Deus sabe! 😅
> 
> **Contexto:**  
> O código deste projeto é de 2018, quando estávamos ainda aprendendo. Atualmente, temos capacidade para desenvolver algo totalmente diferente e com muito mais qualidade.

**S.O.S Cidadão** é um site desenvolvido por alunos do curso Técnico em Informática para Internet do Instituto Técnico de Barueri.

---

## Sobre o Projeto

O objetivo do site é aproximar os munícipes da prefeitura da cidade, facilitando a comunicação direta entre eles. A plataforma ajuda a prefeitura a agilizar respostas a críticas e elogios, eliminando burocracias comuns nos canais tradicionais.

Criamos uma interface inspirada em redes sociais, proporcionando um ambiente web confortável e intuitivo, onde os usuários se sintam à vontade para interagir.

---

## Funcionalidades

Principais funcionalidades do sistema:

- **Cadastro, Login e Recuperação de Senha**
- **Publicações:**
  - Enviar publicações
  - Curtir / Descurtir publicações
  - Comentar publicações
  - A prefeitura pode responder diretamente
  - Denunciar publicações para administradores
  - Salvar publicações para visualizar depois
- **Notificações para usuários comuns:**
  - Comentários em suas publicações
  - Curtidas em suas publicações
  - Respostas da prefeitura às suas publicações
  - Atualizações em publicações salvas
- **Notificações para administradores/moderadores:**
  - Denúncias em publicações
  - Denúncias em comentários
- **Perfil do usuário:**
  - Trocar foto de perfil
  - Trocar foto de capa
- **Busca e filtragem de publicações**
- **Gerenciamento pelo administrador:**
  - Adicionar moderadores
  - Adicionar usuários da prefeitura
  - Banir usuários
  - Editar ou remover publicações e comentários de qualquer usuário
- **Gestão da prefeitura:**
  - Adicionar funcionários
  - Responder publicações em nome da prefeitura

---

## Rotinas de CRUD

Todas as operações de CRUD (Criar, Ler, Atualizar e Deletar) estão implementadas. Ou seja, o usuário pode editar seu nome, alterar sua senha, editar ou excluir comentários e publicações feitas por ele.

---

## Níveis de Usuários

Os níveis de acesso no sistema são:

- **Administrador:** Responsável pela manutenção da ordem no site. Pode visualizar denúncias, remover publicações, banir usuários, adicionar ou remover moderadores e usuários da prefeitura.
  
- **Moderador:** Possui quase as mesmas permissões do administrador, exceto a criação ou remoção de usuários.
  
- **Prefeitura:** Tem acesso a todas as publicações (respondidas ou não) e pode respondê-las diretamente. Pode adicionar funcionários para ajudar nessa tarefa.
  
- **Funcionário da Prefeitura:** Pode responder publicações em nome da prefeitura e visualizar todas as publicações.
  
- **Usuário Comum:** Pode criar publicações, comentar, curtir, denunciar e realizar todas as operações de CRUD nas suas próprias publicações e comentários.

---

## Tecnologias e Detalhes Técnicos

- Navegação dinâmica implementada com **AJAX**
- Sistema de **URLs amigáveis**
- Código backend em **PHP orientado a objetos (POO)**
