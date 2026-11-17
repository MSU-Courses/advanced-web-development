{extends file='layout.tpl'}

{block name=title}Обратная связь{/block}

{block name=content}
    <h1>Обратная связь</h1>
    {if $sent}
        <p>Спасибо, {$form.name}! Сообщение получено.</p>
    {else}
        <form method="post">
            {include file='field.tpl' label='Имя' name='name' value=$form.name error=$errors.name|default:''}
            {include file='field.tpl' label='Email' name='email' value=$form.email error=$errors.email|default:''}
            {include file='field.tpl' label='Сообщение' name='message' value=$form.message error=$errors.message|default:'' multiline=true}
            <button type="submit">Отправить</button>
        </form>
    {/if}
{/block}
