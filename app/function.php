<?php

function errors_for($attribute,$errors)
{
    if($attribute == 'success')
    {
        return $errors->first($attribute, '<div class="alert alert-success alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>:message</div>');
    }
    elseif($attribute == 'fail')
    {
        return $errors->first($attribute, '<div class="alert alert-danger alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>:message</div>');
    }
    else
        return $errors->first($attribute, '<div class="alert alert-warning alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>:message</div>');
    //   return $errors->first($attribute, '<div class="dm3-alert dm3-alert-error">:message</div>');
}


