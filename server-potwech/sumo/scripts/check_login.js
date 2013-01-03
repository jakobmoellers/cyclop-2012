<!--

function check(frm) {
	
	msg1=''; 
	msg2='';
	error=0;
	
    if (frm.sumo_pwd.value == '') 
    {
      msg1 = 'Password';
      error=1;
    }
    
	if (frm.sumo_user.value == '') 
	{
      msg2 = 'User';
      error=1;
    }
    
	if (error==1) 
	{ 
		if (msg1 != '' && msg2 != '') 
		{ 
			if (frm.sumo_user.value.length > 25) 
			{ 
				alert('Too much characters !'); 
			}
			else 
			{ 
				alert('Please enter User and Password !'); 
				frm.sumo_user.focus(); 
			}
		}
		else 
		{ 
			if (frm.sumo_user.value.length > 25) 
			{ 
				alert('Too much characters !'); 
			}
			else 
			{ 
				alert('Please enter '+ msg1 + msg2 +' !'); 
						
				if (frm.sumo_pwd.value == '')
				{ 
					frm.sumo_pwd.focus(); 
				}
				else 
				{ 
					frm.sumo_user.focus(); 
				}	
			}
		}
	}	
	else { error=0; }
}

//-->