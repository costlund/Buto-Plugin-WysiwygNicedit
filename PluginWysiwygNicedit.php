<?php
/**
 * Transform a textarea to html editor via javascript.
 * PluginWfForm_v2: Set param items/_id_/html to true to transform a textarea to html editor.
 * http://nicedit.com/
 */
class PluginWysiwygNicedit{
  public static function widget_include($data){
    $element = array();
    $element[] = wfDocument::createHtmlElement('script', null, array('src' => '/plugin/wysiwyg/nicedit/nicEdit.js', 'type' => 'text/javascript'));
    wfDocument::renderElement($element);
  }
  /**
   * Create a script element to transform a textarea to html editor.
   * @param string $element_id
   * @return widget
   */
  public function getTextareaScript($element_id, $buttonList = array()){
    //http://wiki.nicedit.com/w/page/515/Configuration%20Options
    if(!sizeof($buttonList)){
      $buttonList = $this->buttonList;
    }
    $buttonList = json_encode($buttonList);
    $str = null;
    $str .= "if(typeof nicEditor != 'function'){alert('Nic Editor javascript file is not included.');};";
    $str .= "function func_nic_editor_$element_id(){";
    $str .= "var nic_editor_$element_id = new nicEditor({iconsPath : '/plugin/wysiwyg/nicedit/nicEditorIcons.gif', fullPanelzzz: true, buttonList: $buttonList}).panelInstance('$element_id');";
    $str .= "document.getElementById('$element_id').parentNode.getElementsByClassName('nicEdit-main')[0].onblur = function(){ document.getElementById('$element_id').value = document.getElementById('$element_id').parentNode.getElementsByClassName('nicEdit-main')[0].innerHTML };";
    $str .= "};";
    $str .= "bkLib.onDomLoaded(";
    $str .= ");";
    $str .= "func_nic_editor_$element_id()";
    return wfDocument::createHtmlElement('script', $str, array('type' => 'text/javascript'));
  }
  /**
   * Transform textarea to html editor.
   * Remark: When loading this widget the content should not be rendered outside the visible area.
   */
  public static function widget_textarea_script($data){
    wfPlugin::includeonce('wf/array');
    $data = new PluginWfArray($data);
    $nicedit = new PluginWysiwygNicedit();
    $element = $nicedit->getTextareaScript($data->get('data/element_id'), $data->get('data/buttonList'));
    wfDocument::renderElement(array($element));
  }
  /**
   * List of buttons.
   * 180611: Could not get these buttons to work when loading form via ajax, 'link','unlink','xhtml'.
   */
  private $buttonList = array('bold','italic','underline','left','center','right','fontSize','fontFamily','fontFormat','forecolor','bgcolor');
}