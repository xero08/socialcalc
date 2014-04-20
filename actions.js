var lastId=0;

function c(val)
{
	$('#d').val(val);
}
function v(val)
{
	z=$('#d').val();
	if(z=='Error'){
		$('#d').val(val);
		return;
	}
	if(val=="bksp"){
		$('#d').val(z.substr(0,z.length-1));	
		return;
	}
	else if(val=="inv"){
		$('#d').val("1/"+z);
		e();
		return;
	}
	else if(val=="sqrt"){
		try{
			r=eval(z);
			if(r<0){
				c('Error');
				sendLog('sqrt('+z+')',"Input resulted in an error");
				return;
			}
			$('#d').val(Math.sqrt(r));
			sendLog('sqrt('+z+')',Math.sqrt(r));
			return;
		}
		catch(e){
			c('Error');
			sendLog($('#d').val(),"Input resulted in an error");
			return;
		}		
	}
	$('#d').val(z+val);
}
function e() 
{ 
	try 
	{ 
	  h=$('#d').val();
	  if(h=='')
	  	return;
	  if(h.indexOf('+')==-1&&h.indexOf('-')==-1&&h.indexOf('*')==-1&&h.indexOf('/')==-1)
	  	return;
	  c(eval($("#d").val())) 
	  sendLog(h,eval($('#d').val()));
	} 
	catch(e) 
	{
	  c('Error');
	  sendLog($('#d').val(),"Input resulted in an error");
	}		
}

function sendLog(input,answer){
	$.ajax({
		method:'POST',
		url:'sendLog.php',
		data:'input='+encodeURIComponent(input)+'&answer='+answer,
		success:function(data){
			obj=$.parseJSON(data);
			lastId=obj.ID;
			addLog(input,answer,obj.timeofCalc,obj.IPAddr,lastId);
		},
		error:function(data){
			alert(data);
		}
	});
}

function addLog(a,b,c,d,e){
	$('#logInner').append('<tr><td><span class="hidden" id="idField">'+e+'</span><p>Calcuation from IP Address <span class="highlight">'+d+'</span> at <span class="highlight">'+c+'</span></p><p>Input <span class="highlight">'+a+'</span> Output <span class="highlight">'+b+'</span></p></td></tr>');
}

(function worker() {
  $.ajax({
  	method:'GET',
    url: 'getLog.php', 
    data:{after:lastId},
    success: function(data) {       
       obj=$.parseJSON(data);
       if(obj['Calcs'].length==0)
       	  return;
       lastId=obj['Calcs'][obj['Calcs'].length-1][0];
       for(i=0;i<obj['Calcs'].length;i++){
       	   t=obj["Calcs"][i];
       	   if($('#logInner tr:last').find('td').eq(0).find('span').eq(0).text()==t[0])
       	   	   continue;
       	   addLog(t[1],t[2],t[4],t[3],t[0]);
       }       	
    },
    complete: function() {
      setTimeout(worker, 5000);
    }
  });
})();

function clearLog(){
	$('#logInner').find('tr').each(function(){
		$(this).remove();
	});
}