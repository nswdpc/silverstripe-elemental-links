<div class="{$ElementStyles}">
    <div class="links-element__content">
	    <% if $ShowTitle %>
            <h2 class="content-element__title">{$Title.XML}</h2>
        <% end_if %>
	   {$HTML}
        <ul class="links-element__list">
            <% loop $DerivedElementLinksList %>
                <li>
                    <a href="{$URL}" rel="noopener noreferrer"<% if $OpenInNew %> target="_blank"<% end_if %>>{$Title}</a>
                </li>
            <% end_loop %>
        </ul>
    </div>
</div>
