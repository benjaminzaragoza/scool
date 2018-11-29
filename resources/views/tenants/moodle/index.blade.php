@extends('tenants.layouts.app')

@section('content')
    <moodle-users :users="{{ $users }}"></moodle-users>
@endsection


<script>
  import MoodleUsers from '../../../tenant_js/components/moodle/users/MoodleUsersComponent'
  export default {
    components: {
      'moodle-users': MoodleUsers
    }
  }
</script>
