<template>
  <li class="nav-item">
    <Link class="nav-link" :href="route('customer.dashboard')">{{
      __("Dashboard")
    }}</Link>
  </li>

  <li class="nav-item" v-if="$page.props.is_ordering_enable">
    <Link class="nav-link" :href="route('customer.tasks.index')">{{ __("Tasks") }}</Link>
  </li>
  <li class="nav-item" v-if="$page.props.is_bidding_enable">
    <Link class="nav-link" :href="route('customer.bidRequests.index')">{{
      __("Bid Requests")
    }}</Link>
  </li>
  <!-- <li class="nav-item">
    <Link class="nav-link" :href="route('customer.projects.index')">{{ __("Projects") }}</Link>
  </li>
   -->
  <li class="nav-item dropdown">
    <a
      class="nav-link dropdown-toggle"
      href="#"
      id="dropdown01"
      data-bs-toggle="dropdown"
      aria-expanded="false"
      >{{ __("Financial") }}</a
    >
    <ul class="dropdown-menu" aria-labelledby="dropdown01">
      <li>
        <Link :href="route('customer.payments.index')" class="dropdown-item">{{
          __("Payment Receipts")
        }}</Link>
      </li>
      <li>
        <Link :href="route('customer.payments.pendingApprovals')" class="dropdown-item">{{
          __("Payments Pending Approval")
        }}</Link>
      </li>
      <li>
        <Link :href="route('customer.invoices.index')" class="dropdown-item">{{
          __("Invoices")
        }}</Link>
      </li>
      <li>
        <Link :href="route('customer.transactions.index')" class="dropdown-item">{{
          __("Transactions")
        }}</Link>
      </li>
    </ul>
  </li>

  <li class="nav-item">
    <Link class="nav-link" :href="route('customer.transactions.funds.create')">
      {{ __("Add Fund") }}</Link
    >
  </li>

  <li class="nav-item">
    <Link class="nav-link" :href="route('checkout')">
      <i class="fa-solid fa-cart-shopping fs-8"></i> {{ __("Your Cart") }}</Link
    >
  </li>


  <!-- <li class="nav-item dropdown">
    <a
      class="nav-link dropdown-toggle"
      href="#"
      id="dropdown01"
      data-bs-toggle="dropdown"
      aria-expanded="false"
      >{{ __("Ai Generate Content") }}</a
    >
    <ul class="dropdown-menu" aria-labelledby="dropdown01">
      <li>
        <Link :href="route('customer.aIGeneratedContents.create')" class="dropdown-item">
          {{ __("Create New") }}</Link
        >
      </li>
      <li>
        <Link :href="route('customer.aIGeneratedContents.index')" class="dropdown-item">
          {{ __("Your Contents") }}</Link
        >
      </li>
      <li>
        <Link :href="route('customer.subscriptions.index')" class="dropdown-item">{{
          __("Manage Subscription")
        }}</Link>
      </li>
    </ul>
  </li> -->
</template>

<script>
export default {};
</script>
