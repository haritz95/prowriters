<template>
  <li class="nav-item">
    <Link class="nav-link" :href="route('author.dashboard')">{{ __("Dashboard") }}</Link>
  </li>
  <li class="nav-item">
    <Link class="nav-link" :href="route('author.tasks.index')">{{ __("My Tasks") }}</Link>
  </li>

  <li class="nav-item" v-if="$page.props.is_bidding_enable">
    <Link class="nav-link" :href="route('author.bidRequests.index')">{{
      __("Bid Requests")
    }}</Link>
  </li>
  <li class="nav-item" v-if="$page.props.auth_user.is_self_assigning_tasks_enable">
    <Link class="nav-link" :href="route('author.find.work.index')">{{
      __("Find Work")
    }}</Link>
  </li>

  <li class="nav-item">
    <Link class="nav-link" :href="route('author.calendar')">{{ __("Calendar") }}</Link>
  </li>
  <li class="nav-item dropdown">
    <a
      class="nav-link dropdown-toggle"
      href="#"
      id="navbarDropdown"
      role="button"
      data-bs-toggle="dropdown"
      aria-expanded="false"
    >
      {{ __("Earnings") }}
    </a>
    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
      <li>
        <Link class="dropdown-item" :href="route('author.paymentRequests.create')">{{
          __("Request For Payment")
        }}</Link>
      </li>
      <li>
        <Link class="dropdown-item" :href="route('author.paymentRequests.index')">{{
          __("List of Payment Requests")
        }}</Link>
      </li>
    </ul>
  </li>
  <li class="nav-item">
    <Link class="nav-link" :href="route('author.ratings.index')">{{
      __("Ratings")
    }}</Link>
  </li>

  <li class="nav-item">
    <Link class="nav-link" :href="route('author.messageThreads.index')">{{
      __("Messages")
    }}</Link>
  </li>

  <li class="nav-item">
    <Link class="nav-link" :href="route('author.announcements.index')">{{
      __("Announcements")
    }}</Link>
  </li>
</template>

<script>
export default {};
</script>
