<?php

class MemcacheWrapper {
	private $memcache;
	private $prefix;
   //use temp for mile limits, gambing limits
	
	public function __construct($prefix='') {
		$this->prefix = $prefix;
		$this->memcache = new Memcache(); 
	}
    
    public function addServer($one, $two) {
    	$this->memcache->addServer($one, $two);
    }
    
 	public function set($one, $two, $three = null, $four = null) {
 		//echo "1: $one 2: $two 3: $three 4: $four  ";
		if($four != null) {
			//echo "   setting one: $one two: $two three: $three four: $four     ";
			$this->memcache->set($one . $this->prefix, $two, $three, $four);
		}
		else {
			//echo "   setting one: $one two: $two       ";
			$this->memcache->set($one . $this->prefix, $two);
		}
	}
	
	public function delete($one, $two)	{
		$this->memcache->delete($one . $this->prefix, $two);
	}
	
	public function flush() {
		$this->memcache->flush();
	}
	
	public function get($one) {
			$prefix = $this->prefix;
			//echo "$one $prefix";
			return $this->memcache->get($one . $prefix);
	}
	
	public function add($one, $two, $three = null, $four = null) {
		if($four != null) {
			$this->memcache->add($one . $this->prefix, $two, $three, $four);
		}
		else {
			$this->memcache->add($one . $this->prefix, $two);
		}	}

}

?>