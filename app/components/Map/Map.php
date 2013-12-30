<?php
namespace Components;
/**
 * Description of FrindsButton
 *
 * @author Skalda
 */
class Map extends \Nette\Application\UI\Control {

    static $mapurl = "http://maps.googleapis.com/maps/api/staticmap?size={size}&sensor=false&path={path}";
    private $points;
    private $width = 800;
    private $height = 450;
    
    public function addPoint($x, $y) {
	$this->points[] = $x.','.$y;
    }
    
    public function setSize($width, $height) {
	$this->width = $width;
	$this->height = $height;
    }
    
    /**
     * (non-phpDoc)
     *
     * @see Nette\Application\Control#render()
     */
    public function render() {
	$url = self::$mapurl;
	$url = preg_replace('~{size}~', $this->width.'x'.$this->height, $url);
	$url = preg_replace('~{path}~', implode("|", $this->points), $url);
	$this->template->url = $url;
	$this->template->width = $this->width;
	$this->template->height = $this->height;
	$this->template->setFile(dirname(__FILE__) . '/map.latte');
	$this->template->render();
    }

}