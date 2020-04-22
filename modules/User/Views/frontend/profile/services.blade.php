<?php
$types = get_bookable_services();
if(empty($types)) return;
?>

@foreach($types as $type=>$moduleClass)
    @if(view()->exists(ucfirst($type).'::frontend.profile.service'))
        @include(ucfirst($type).'::frontend.profile.service')
    @endif
@endforeach
