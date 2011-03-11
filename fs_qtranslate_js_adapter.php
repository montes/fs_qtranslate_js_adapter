<?php

function fs_qtranslate_js_adapter()
{
  global $q_config;

  $content = "<script type=\"text/javascript\">\n// <![CDATA[\n";
  $content .= "jQuery(document).ready(function($) {\n";
  $content .= $q_config['js']['qtrans_is_array'];
  $content .= $q_config['js']['qtrans_xsplit'];
  $content .= $q_config['js']['qtrans_split'];

  $content .= "\n\tcreate_qtranslate_fields_for = function(id) {\n";
  $content .= "\t\tvar source = $('#' + id)\n";
  $content .= "\t\tvar clones = new Object;\n";
  $content .= "\t\tvar results = qtrans_split(source.val());\n\n";
  $content .= "\t\tvar updates = new Object;\n";
  $content .= "\t\tsource.removeClass('translatable').hide();\n\n";

  foreach($q_config['enabled_languages'] as $language) {
    $content .= "\t\tclones['" . $language . "'] = source.clone();\n";
    $content .= "\t\t$(clones['" . $language . "']).removeAttr('name');\n";
    $content .= "\t\t$(clones['" . $language . "']).attr('id', $(clones['" . $language . "']).attr('id') + '_" . $language . "');\n";
    $content .= "\t\t$(clones['" . $language . "']).attr('value', results['" . $language . "']);\n";
    $content .= "\t\t$(clones['" . $language . "']).change(function() {
      \t\t\tupdates['" . $language . "'] = $(this).val();
      \t\t\tsync_text = '';
      
      \t\t\tfor (lang in results) {
        \t\t\tsync_text += '<!--:' + lang + '-->';
        
        \t\t\tif ( updates[lang] ) sync_text += updates[lang];
        \t\t\telse sync_text += results[lang];
        
        \t\t\tsync_text += '<!--:-->';
      \t\t\t}
      
      \t\t\tsource.attr('value', sync_text);      
    \t\t})\n";
    
    // Insert before the source
    $content .= "\t\t$(clones['" . $language . "']).after('<span class=\"description\">" . $q_config['language_name'][$language] . "</span><br />').insertBefore(source).show();\n\n";
  }
  $content .= "\t}\n\n";
  
  $content .= "\t$('.translatable').each(function() {
    \t\tcreate_qtranslate_fields_for($(this).attr('id'));
  \t});\n";
  
  $content .= "});\n";
  $content .= "// ]]>\n</script>\n";
  
  echo $content;
}
`>