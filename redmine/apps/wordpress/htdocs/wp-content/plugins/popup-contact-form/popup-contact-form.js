/**
 *     Popup contact form
 *     Copyright (C) 2011 - 2014 www.gopiplus.com
 *     http://www.gopiplus.com/work/2012/05/18/popup-contact-form-wordpress-plugin/
 * 
 *     This program is free software: you can redistribute it and/or modify
 *     it under the terms of the GNU General Public License as published by
 *     the Free Software Foundation, either version 3 of the License, or
 *     (at your option) any later version.
 * 
 *     This program is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *     GNU General Public License for more details.
 * 
 *     You should have received a copy of the GNU General Public License
 *     along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

var http_req = false;
function PopupContactPOSTRequest(url, parameters) 
{
  http_req = false;
  if (window.XMLHttpRequest) 
  {
	 http_req = new XMLHttpRequest();
	 if (http_req.overrideMimeType) 
	 {
		http_req.overrideMimeType('text/html');
	 }
  } 
  else if (window.ActiveXObject) 
  {
	 try 
	 {
		http_req = new ActiveXObject("Msxml2.XMLHTTP");
	 } 
	 catch (e) 
	 {
		try 
		{
		   http_req = new ActiveXObject("Microsoft.XMLHTTP");
		} 
		catch (e) {}
	 }
  }
  if (!http_req) 
  {
	 alert('Cannot create XMLHTTP instance');
	 return false;
  }
  http_req.onreadystatechange = PopupContactContents;
  http_req.open('POST', url, true);
  http_req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  http_req.setRequestHeader("Content-length", parameters.length);
  http_req.setRequestHeader("Connection", "close");
  http_req.send(parameters);
}

function PopupContactContents() 
{
  //alert(http_req.readyState);
  //alert(http_req.responseText);
  if (http_req.readyState == 4) 
  {
	 if (http_req.status == 200) 
	 {
		if(http_req.responseText == "Invalid security code.")
		{
			alert(http_req.responseText);
			result = http_req.responseText;
			document.getElementById('PopupContact_alertmessage').innerHTML = result;
		}
		else
		{
			alert(http_req.responseText);
			result = http_req.responseText;
			document.getElementById('PopupContact_alertmessage').innerHTML = "";   
			document.getElementById("PopupContact_email").value = "";
			document.getElementById("PopupContact_name").value = "";
			document.getElementById("PopupContact_message").value = "";
		}
	 } 
	 else 
	 {
		alert('There was a problem with the request.');
	 }
  }
}

function PopupContact_Submit(obj, url) 
{
	_e=document.getElementById("PopupContact_email");
	_n=document.getElementById("PopupContact_name");
	_m=document.getElementById("PopupContact_message");
	
	if(_n.value=="")
	{
		alert("Please Enter Your Name.");
		_n.focus();
		return false;    
	}
	else if(_e.value=="")
	{
		alert("Please Enter Your Email.");
		_e.focus();
		return false;    
	}
	else if(_e.value!="" && (_e.value.indexOf("@",0)==-1 || _e.value.indexOf(".",0)==-1))
	{
		alert("Please Enter Valid Email.")
		_e.focus();
		_e.select();
		return false;
	} 
	else if(_m.value=="")
	{
		alert("Please Enter Your Message.");
		_m.focus();
		return false;    
	}
	document.getElementById('PopupContact_alertmessage').innerHTML = "Sending..."; 
	var str = "PopupContact_name=" + encodeURI( document.getElementById("PopupContact_name").value ) + "&PopupContact_email=" + encodeURI( document.getElementById("PopupContact_email").value ) + "&PopupContact_message=" + encodeURI( document.getElementById("PopupContact_message").value ) + "&PopupContact_captcha=nocaptcha";
	PopupContactPOSTRequest(url+'popup-contact-save.php', str);
}
