<template>
  <li class="nav-item">
    <Link class="nav-link" :href="route('admin.dashboard')">{{ __("Dashboard") }}</Link>
  </li>

  <li class="nav-item">
    <Link class="nav-link" :href="route('admin.tasks.index')">{{ __("Tasks") }}</Link>
  </li>

  <li class="nav-item" v-if="$page.props.is_bidding_enable">
    <Link class="nav-link" :href="route('admin.bidRequests.index')">{{
      __("Bids Requests")
    }}</Link>
  </li>

  <li class="nav-item">
    <Link class="nav-link" :href="route('admin.messageThreads.index')">{{
      __("Messages")
    }}</Link>
  </li>

  <li class="nav-item dropdown">
    <Link
      class="nav-link dropdown-toggle"
      href="#"
      id="people"
      data-bs-toggle="dropdown"
      aria-expanded="false"
      >{{ __("People") }}</Link
    >

    <div class="dropdown-menu" aria-labelledby="people">
      <Link class="dropdown-item" :href="route('admin.customers.index')">{{
        __("Customers")
      }}</Link>
      <Link class="dropdown-item" :href="route('admin.authors.index')">{{
        __("Authors")
      }}</Link>
      <Link
        class="dropdown-item"
        :href="route('admin.users.index')"
        v-if="hasRole('super_admin')"
        >{{ __("Admin Users") }}</Link
      >
    </div>
  </li>

  <li class="nav-item dropdown" v-if="hasRole('super_admin')">
    <Link
      class="nav-link dropdown-toggle"
      href="#"
      id="payments"
      data-bs-toggle="dropdown"
      aria-expanded="false"
      >{{ __("Transactions") }}</Link
    >

    <div class="dropdown-menu" aria-labelledby="payments">
      <Link class="dropdown-item" :href="route('admin.payments.create')">{{
        __("Payment Entry")
      }}</Link>
      <Link class="dropdown-item" :href="route('admin.payments.index')">{{
        __("Received Payments")
      }}</Link>
      <Link
        class="dropdown-item"
        :href="route('admin.payments.pendingApprovals.index')"
        >{{ __("Pending Payment Approvals") }}</Link
      >
      <Link class="dropdown-item" :href="route('admin.transactions.index')">{{
        __("Wallet Transactions")
      }}</Link>
      <Link class="dropdown-item" :href="route('admin.walletAdjustments.index')">{{
        __("Wallet Adjustments")
      }}</Link>
    </div>
  </li>

  <li class="nav-item dropdown">
    <Link
      class="nav-link dropdown-toggle"
      href="#"
      id="manage"
      data-bs-toggle="dropdown"
      aria-expanded="false"
      >{{ __("Manage") }}</Link
    >

    <div class="dropdown-menu" aria-labelledby="manage">
      <Link
        class="dropdown-item"
        :href="route('admin.settings.general')"
        v-if="hasRole('super_admin')"
        >{{ __("Settings") }}</Link
      >
      <Link
        class="dropdown-item"
        :href="route('admin.business')"
        v-if="hasRole('super_admin')"
        >{{ __("Services & Pricing") }}</Link
      >
      <Link class="dropdown-item" :href="route('admin.announcements.index')">{{
        __("Announcements")
      }}</Link>
      
      <Link class="dropdown-item" :href="route('admin.manage.content')">{{
        __("Website & Content")
      }}</Link>

      <Link
        class="dropdown-item"
        :href="route('admin.bills.index')"
        v-if="hasRole('super_admin')"
        >{{ __("Bills from Authors") }}</Link
      >
      <Link
        class="dropdown-item"
        :href="route('admin.invoices.index')"
        v-if="hasRole('super_admin')"
        >{{ __("Invoices") }}</Link
      >
      <Link class="dropdown-item" :href="route('admin.applicants.index')">{{
        __("Job Applicants")
      }}</Link>
      <Link
        class="dropdown-item"
        :href="route('admin.coupons.index')"
        v-if="hasRole('super_admin')"
        >{{ __("Discount Coupons") }}</Link
      >

      <Link class="dropdown-item" :href="route('admin.aIGeneratedContents.index')">
        {{ __("AI Generated Content") }}</Link
      >
    </div>
  </li>

  <li class="nav-item dropdown" v-if="hasRole('super_admin')">
    <Link
      class="nav-link dropdown-toggle"
      href="#"
      id="people"
      data-bs-toggle="dropdown"
      aria-expanded="false"
      >{{ __("Reports") }}</Link
    >
    <div class="dropdown-menu" aria-labelledby="people">
      <Link class="dropdown-item" :href="route('admin.reports.income.statement')">{{
        __("Income Statement")
      }}</Link>
      <Link class="dropdown-item" :href="route('admin.reports.wallet.balance')">{{
        __("Balance in Wallet")
      }}</Link>
      <Link class="dropdown-item" :href="route('admin.reports.ratings.index')">{{
        __("Ratings By Customer")
      }}</Link>
      <!-- <Link class="dropdown-item" :href="route('admin.reports.activityLog.index')">{{ __("Activity Log") }}</Link> -->
    </div>
  </li>
</template>

<script>
export default {};
</script>
