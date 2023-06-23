<div class="{$ElementStyles}">
    <div class="links-element__content">
	    <% if $ShowTitle %>
            <h2 class="content-element__title">{$Title.XML}</h2>
        <% end_if %>
	   {$HTML}
        <ul class="links-element__list">
            <% loop $ElementLinks %>
                <li>
                    <a href="{$LinkURL}">{$Title}</a>
                </li>
            <% end_loop %>
        </ul>
    </div>
</div>
