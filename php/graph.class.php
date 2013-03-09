<?php
require_once('config.php');
require_once('error_handler.php');

class Graph
{
	private $mMysqli;
	private $type;
	private $ymax;
	private $xmax;
	private $yaxis;
	private $xaxis;
	private $yplot;
	private $xplot;
	private $border;
	private $labelSize;
	private $increment;
	private $id;
	private $series;
	private $maxNum;
	private $minNum;
	private $numRange;
	private $ycoefficient;
	private $xcoefficient;
	
	private $values = array();
	
	function __construct($type, $height=160, $width=1200)
	{
		$this->mMysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
		$this->type = $type;
		$this->ymax = $height;
		$this->xmax = $width;
		$this->border = $height / 4;
		$this->yaxis = 0;
		$this->xaxis = $this->border;
		$this->labelSize = 11;
		$this->yplot = $this->ymax - $this->xaxis;
		$this->xplot = $this->xmax - $this->yaxis;
	}
	
	public function __destruct()
	{
		$this->mMysqli->close();
	}
	
	public function getCategoriesGraph($id)
	{
		$this->id = $this->mMysqli->real_escape_string($id);		
		$this->values = $this->getCategoriesCount($this->id);
		$this->minNum = 0;	
		$this->maxNum = max($this->values) + 2;
		$this->increment = (floor($this->maxNum / 4) < 1) ? 1 : floor($this->maxNum / 4); 	
		$this->numRange = $this->maxNum - $this->minNum;
		$this->series = count($this->values);
		$this->xcoefficient = $this->xplot / ($this->series);		
		$this->ycoefficient = $this->yplot / $this->numRange;

		$htmlReference = $this->generatePlotArea();

		return $htmlReference;
	}
	
	function generateplotArea()
	{
		$svg = '<div id="graph"><p style="float:right;">Graph Type: <select><option value="bar">Bar</option><option value="line">Line</option></select></p><h2>Progress</h2>
					<svg style="height: ' . ($this->ymax + 20) . 'px;" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="none" width="100%" height="100%">
						<filter id="dropshadow" height="130%">
  							<feGaussianBlur stdDeviation="1" />
  							<feOffset dx="1.0" dy="1.0" result="offsetblur"/>
						</filter>
						<defs id="defs">
							<clipPath id="graphCat">
	   							<rect height="' . $this->ymax  . '" width="' . $this->xmax . '" y="0" x="0"></rect>
	  						</clipPath>
						</defs>						
						<rect fill="#FFFFFF" stroke-width="1" stroke="none" height="' . $this->ymax . '" width="' . $this->xmax . '" y="0" x="0"></rect>
						<g clip-path="url(#graphCat)">
							<g>';
		
		for ($i = 1; $i < $this->maxNum; $i = $i + $this->increment) {
			$svg = $svg . '<rect fill="#EFEFEF" stroke-width="0" stroke="none" height="1" width="' . $this->xplot . '" y="' . (($this->maxNum - $i) * $this->ycoefficient) . '" x="' . $this->yaxis . '"></rect>';
			$svg = $svg . '<text fill="#444444" stroke-width="0" stroke="none" font-size="' . $this->labelSize . '" font-family="Arial" y="' . (($this->maxNum - $i) * $this->ycoefficient + $this->labelSize) . '" x="0" text-anchor="left">' . $i . '</text>';			
		}				
		$svg =	$svg .	'</g><g id="plot">';
		switch ($this->type) {
			case 'bar':	
				$svg = $this->generateBarPlot($svg);
				break;
			case 'line':
				$svg = $this->generateLinePlot($svg);
				break;
			default:
				$svg = $this->generateLinePlot($svg);
				break;	
		}					
		$svg =	$svg .	'</g><g><rect fill="#333333" stroke-width="0" stroke="none" height="1" width="' . $this->xplot . '" y="' . $this->yplot . '" x="' . $this->yaxis . '"></rect></g></g>';
		$count = 0;
		foreach ($this->values as $key => $value) {
			$svg =	$svg .	'<g><text fill="#444444" stroke-width="0" stroke="none" font-size="' . $this->labelSize . '" font-family="Arial" y="' . ($this->ymax - $this->labelSize) . '" x="' . (($count * $this->xcoefficient) + $this->yaxis + $this->xcoefficient / 2) . '" text-anchor="middle">' . $key . '</text></g>';
			++$count;
		}
		$svg =	$svg .	'</svg></div>';
		
		return $svg;
	}
	
	function generateLinePlot($svg)
	{			
		$svg =	$svg .	'<g><path fill="#058dc7" fill-opacity="0.1" stroke-width="4" stroke="none" d="M' . $this->yaxis . ',' . ($this->maxNum * $this->ycoefficient) . 'L';
		$count = 0;
		foreach ($this->values as $value) {
			$svg = $svg . (($count * $this->xcoefficient) + $this->yaxis + $this->xcoefficient / 2) . ',' . (($this->maxNum - $value) * $this->ycoefficient) . 'L';
			++$count;
		}
		$svg =	$svg .	'"></path></g><g><path fill="none" fill-opacity="1" stroke-width="4" stroke="#058dc7" d="M';
		$count = 0;
		foreach ($this->values as $value) {
			$svg = $svg . (($count * $this->xcoefficient) + $this->yaxis + $this->xcoefficient / 2) . ',' . (($this->maxNum - $value) * $this->ycoefficient) . 'L';
			++$count;
		}
		$svg =	$svg .	'"></path></g></g><g>';
		$count = 0;
		foreach ($this->values as $value) {
			$svg =	$svg .	'<circle class="plotNode" fill="#058dc7" stroke-width="0" stroke="none" r="4.5" cy="' . (($this->maxNum - $value) * $this->ycoefficient) . '" cx="' . (($count * $this->xcoefficient) + $this->yaxis + $this->xcoefficient / 2) . '"></circle>';
			++$count;
		}
		
		return $svg;
	}

	function generateBarPlot($svg)
	{
		$theight = $this->labelSize + 2;
		$pwidth = 50;
		$ppad = 5;
		$count = 0;
		foreach ($this->values as $key => $value) {
			$list = array();
			$list = $this->getCategoriesList($this->id, $key);

			$width = $this->xcoefficient - $this->xcoefficient / 2;
			$height = $value * $this->ycoefficient;
			$x = ($count * $this->xcoefficient) + $this->yaxis + $this->xcoefficient / 4;
			$y = ($this->maxNum - $value) * $this->ycoefficient;
			
			$pheight = count($list) * $theight + $ppad;
			$px = ($width - $pwidth) / 2;
			
			$tx = $width / 2;
			
			$svg = $svg . '<rect id="' . $key . '" fill="#058dc7"  fill-opacity="1.0" stroke-width="1" stroke="none" width="' . $width . '" height="' . $height . '" x="' . $x . '" y="' . $y . '"></rect>';
			$svg = $svg . '<g id="' . $key . '_group" style="display:none;">';	
			$svg = $svg . '<rect id="' . $key . '_shadow" fill="none" fill-opacity="0.0" stroke-width="1.0" stroke="#777777" width="50" height="' . ($pheight + 1) . '" x="' . ($x + $px - 0.5) . '" y="' . ($y - $pheight - $ppad - 0.5) . '" filter="url(#dropshadow)"></rect>';
			$svg = $svg . '<rect id="' . $key . '_border" fill="none" fill-opacity="0.0" stroke-width="1.0" stroke="#777777" width="51" height="' . ($pheight + 1) . '" x="' . ($x + $px - 0.5) . '" y="' . ($y - $pheight - $ppad - 0.5) . '"></rect>';
			$svg = $svg . '<rect id="' . $key . '_details" fill="#FFFFFF" fill-opacity="1.0" stroke-width="0.0" stroke="none" width="50" height="' . $pheight . '" x="' . ($x + $px) . '" y="' . ($y - $pheight - $ppad) . '"></rect>';	

			$c = 0;
			foreach ($list as $k => $v) {
				$svg =	$svg . '<text fill="#000000" stroke-width="0" stroke="none" font-size="' . $this->labelSize . '" font-family="Arial" y="' . ($y - ($c * $theight) - (2 * $ppad)) . '" x="' . ($x + $tx) . '" text-anchor="middle">' . $v . '</text>';
				++$c;
			}
		 
			$svg =	$svg .	'</g>';
			++$count;
		}
				
		return $svg;
	}

	function getCategoriesCount($id)
	{
		// create the return array
		$reference = array();
		// check for valid staff id
		if($id != '') {
			// query to count attendance based on category			
			$query = "SELECT fu.fun,
							 ds.des, 
							 dc.doc, 
							 co.coo, 
							 bi.bim, 
							 na.nav,
							 re.rel
					   FROM staff s
					   		LEFT JOIN (SELECT staff_id, course_id, COUNT(staff_id) AS fun
                     			FROM staff_courses WHERE course_id LIKE '%REV%'
                    			GROUP BY staff_id) AS fu ON s.staff_id = fu.staff_id
							LEFT JOIN (SELECT staff_id, COUNT(staff_id) AS des
                     			FROM staff_courses WHERE course_id LIKE '%DES%'
                    			GROUP BY staff_id) AS ds ON s.staff_id = ds.staff_id
							LEFT JOIN (SELECT staff_id, COUNT(staff_id) AS doc
                     			FROM staff_courses WHERE course_id LIKE '%DOC%'
                    			GROUP BY staff_id) AS dc ON s.staff_id = dc.staff_id
							LEFT JOIN (SELECT staff_id, COUNT(staff_id) AS coo
                     			FROM staff_courses WHERE course_id LIKE '%COO%'
                    			GROUP BY staff_id) AS co ON s.staff_id = co.staff_id
                    		LEFT JOIN (SELECT staff_id, COUNT(staff_id) AS bim
                     			FROM staff_courses WHERE course_id LIKE '%BIM%'
                    			GROUP BY staff_id) AS bi ON s.staff_id = bi.staff_id
                    		LEFT JOIN (SELECT staff_id, COUNT(staff_id) AS nav
                     			FROM staff_courses WHERE course_id LIKE '%NAV%'
                    			GROUP BY staff_id) AS na ON s.staff_id = na.staff_id
                    		LEFT JOIN (SELECT staff_id, COUNT(staff_id) AS rel
                     			FROM staff_courses WHERE course_id LIKE '%REL%'
                    			GROUP BY staff_id) AS re ON s.staff_id = re.staff_id
			  			WHERE s.staff_id = $id";				  
		}
		// execute the query
		$result = $this->mMysqli->query($query);
		// build the JSON response
		$reference = array();
		// see if there are any results
		if($result->num_rows) {			
			// loop through all the fetched content
			while ($row = $result->fetch_array(MYSQLI_ASSOC)) {				
				$reference['Fundamentals'] = $row['fun'];
				$reference['Design'] = $row['des'];
				$reference['Documentation'] = $row['doc'];
				$reference['Coordination'] = $row['coo'];
				$reference['BIM Coordinator'] = $row['bim'];
				$reference['Navisworks'] = $row['nav'];	
				$reference['Elective'] = $row['rel'];	
			}
			// close the database connection as soon as possible
			$result->close();
		}	
		//return the JSON response
		return $reference;	
	}
	
	function getCategoriesList($id, $sub)
	{
		// create the return array
		$reference = array();
		// check for valid staff id
		if($id != '') {
			// query to get the list of courses by subcategory				
			$query = "SELECT sc.course_id
					  FROM staff_courses sc
					  INNER JOIN (SELECT id, subcategory 
					  	FROM courses WHERE subcategory='$sub') 
					  	AS c ON sc.course_id = c.id
					  WHERE sc.staff_id='$id'";	  
		}
		// execute the query
		$result = $this->mMysqli->query($query);
		// build the JSON response
		$reference = array();
		// see if there are any results
		if($result->num_rows) {			
			// loop through all the fetched content
			while ($row = $result->fetch_array(MYSQLI_ASSOC)) {				
				$reference[] = $row['course_id'];
			}
			// close the database connection as soon as possible
			$result->close();
		}	
		//return the JSON response
		return $reference;	
	}
}
?>