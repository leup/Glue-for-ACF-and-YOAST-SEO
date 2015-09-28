/**
 * Tests whether the focus keyword is used in title, body and description
 */
function ystTestFocusKw() {
	// Retrieve focus keyword and trim
	var focuskw = jQuery.trim( jQuery( '#' + wpseoMetaboxL10n.field_prefix + 'focuskw' ).val() );

	focuskw = ystEscapeFocusKw( focuskw ).toLowerCase();

	var postname;
	var url;
	if ( jQuery( '#editable-post-name-full' ).length ) {
		postname = jQuery( '#editable-post-name-full' ).text();
		url = wpseoMetaboxL10n.wpseo_permalink_template.replace( '%postname%', postname ).replace( 'http://', '' );
	}
	var p = new RegExp( '(^|[ \\s\n\r\t\\.,\'\\("\\+;!?:\\-])' + ystRemoveLowerCaseDiacritics( focuskw ) + '($|[\\s\n\r\t.,\'\\)"\\+!?:;\\-])', 'gim' );

	//remove diacritics of a lower cased focuskw for url matching in foreign lang
	var focuskwNoDiacritics = ystRemoveLowerCaseDiacritics( focuskw );
	var p2 = new RegExp( focuskwNoDiacritics.replace( /\s+/g, '[-_\\\//]' ), 'gim' );

	var focuskwresults = jQuery( '#focuskwresults' );
	var metadesc = jQuery( '#wpseosnippet' ).find( '.desc span.content' ).text();

	if ( focuskw !== '' ) {
		var html = '<p>' + wpseoMetaboxL10n.keyword_header + '</p>';
		html += '<ul>';
		if ( jQuery( '#title' ).length ) {
			html += '<li>' + wpseoMetaboxL10n.article_header_text + ystFocusKwTest( jQuery( '#title' ).val(), p ) + '</li>';
		}
		html += '<li>' + wpseoMetaboxL10n.page_title_text + ystFocusKwTest( jQuery( '#wpseosnippet_title' ).text(), p ) + '</li>';
		html += '<li>' + wpseoMetaboxL10n.page_url_text + ystFocusKwTest( url, p2 ) + '</li>';
        var content = 0;
		if ( jQuery( '#content' ).length ) {
            content = jQuery( '#content' ).val();
            jQuery('.yoast-acf').each(function(){
                content += ' ' + this.value;
            });
            html += '<li>' + wpseoMetaboxL10n.content_text + ystFocusKwTest(content, p) + '</li>';
		}
		html += '<li>' + wpseoMetaboxL10n.meta_description_text + ystFocusKwTest( metadesc, p ) + '</li>';
		html += '</ul>';
		focuskwresults.html( html );
	}
	else {
		focuskwresults.html( '' );
	}
}