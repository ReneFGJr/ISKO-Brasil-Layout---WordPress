# Atualização do tema ISKO pelo GitHub

O tema consulta as releases públicas do repositório:

`https://github.com/ReneFGJr/ISKO-Brasil-Layout---WordPress`

Quando uma release possui versão superior à indicada no cabeçalho de `style.css`, a atualização aparece automaticamente em **Painel → Atualizações** e **Aparência → Temas**.

## Publicar uma atualização

1. Altere o campo `Version` no cabeçalho de `style.css`. Use versionamento semântico, por exemplo `1.1.1`.
2. Atualize e teste o código do tema.
3. Faça commit e envie as alterações para o GitHub.
4. No GitHub, abra **Releases → Draft a new release**.
5. Crie uma tag com a mesma versão do `style.css`, de preferência com o prefixo `v`, por exemplo `v1.1.1`.
6. Preencha as notas da versão e publique a release. Não a marque como *pre-release*.

Não é necessário anexar um ZIP manualmente. O atualizador usa o `zipball_url` gerado automaticamente pelo GitHub para a release.

> A versão presente em `style.css` e a versão da tag devem ser iguais. A tag pode conter o prefixo `v`; ele é removido durante a comparação.

## Atualizar pelo WordPress

1. Entre no painel como administrador.
2. Acesse **Painel → Atualizações**.
3. Clique em **Verificar novamente** se a atualização ainda não estiver listada.
4. Selecione o tema ISKO e clique em **Atualizar temas**.

Também é possível iniciar a atualização em **Aparência → Temas**, pela mensagem de nova versão disponível.

Antes de atualizar um site em produção, faça backup do banco de dados e de `wp-content`. Alterações feitas diretamente nos arquivos do tema instalado serão substituídas.

## Funcionamento técnico

- Endpoint consultado: `https://api.github.com/repos/ReneFGJr/ISKO-Brasil-Layout---WordPress/releases/latest`.
- Apenas a release estável mais recente é considerada; rascunhos e pré-releases não são retornados por esse endpoint.
- A resposta da API fica em cache por seis horas para respeitar os limites de requisição do GitHub.
- O WordPress compara a tag da release com o campo `Version` de `style.css` usando `version_compare()`.
- O diretório criado pelo ZIP do GitHub é normalizado para o slug do tema durante a instalação.
- O repositório precisa continuar público para que o WordPress baixe o pacote sem credenciais.

## Primeira release com o atualizador

Esta implementação define o tema como versão `1.1.0`. Para distribuir esta versão, publique no GitHub uma release estável com a tag `v1.1.0` depois de enviar estes arquivos ao repositório.

Sites que ainda usam uma versão anterior receberão a oferta de atualização para `1.1.0`. Um site que já esteja na versão `1.1.0` somente receberá outra atualização quando uma release superior, como `v1.1.1`, for publicada.

## Diagnóstico

Se a atualização não aparecer:

1. Confirme que a release está publicada e não é um rascunho ou pré-release.
2. Confirme que a tag é uma versão válida, como `v1.2.0`.
3. Confirme que a versão da release é superior à versão instalada.
4. Em **Painel → Atualizações**, clique em **Verificar novamente**.
5. Verifique se o servidor consegue acessar `api.github.com` e `github.com` por HTTPS.
6. Verifique o log do WordPress com `WP_DEBUG_LOG` habilitado caso o servidor bloqueie requisições externas ou gravação em `wp-content/themes`.

O mecanismo está em `inc/class-isko-github-updater.php` e é carregado por `functions.php`.
