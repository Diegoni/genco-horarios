  <script src="<?php echo $url['librerias_url']?>chosen/chosen.jquery.js" type="text/javascript"></script>
  <script src="<?php echo $url['librerias_url']?>chosen/docsupport/prism.js" type="text/javascript" charset="utf-8"></script>
  <script type="text/javascript">
    var config = {
      '.chosen-select'           : {},
      '.chosen-select-deselect'  : {allow_single_deselect:true},
      '.chosen-select-no-single' : {disable_search_threshold:10},
      '.chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
      '.chosen-select-width'     : {width:"95%"}
    }
    for (var selector in config) {
      $(selector).chosen(config[selector]);
    }
  </script>


<a href="#" class="scrollup">Scroll</a>
<div id="footer">
	<table width="100%">
	<tbody>
		<tr>
		<td class="left">
			<span class="copyright">
			<?php
			$ar=fopen("version.txt","r") or
			die("No se pudo abrir el archivo");
			//while (!feof($ar))
			//{
				$linea=fgets($ar);
				$lineasalto=nl2br($linea);
				echo $lineasalto;
			//}
			fclose($ar);
			?>
			</span>
		</td>
		<td align="right">
			<a href="http://www.tmsgroup.com.ar/" title="Sitio de TMS Group" target="_blank">
			<span class="copyright">Desarrollado por TMS Group</span>
			</a>
		</td>
		</tr>
	</tbody>
	</table>
</div>