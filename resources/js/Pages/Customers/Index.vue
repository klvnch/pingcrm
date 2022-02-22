<template>
  <div>
    <Head title="Customers" />
    <div class="flex items-center justify-between mb-6">
      <h1 class="mb-8 text-3xl font-bold">Customers</h1>
      <Link class="btn-indigo" href="/customers/create">
        <span>Create</span>
        <span class="hidden md:inline">&nbsp;Customer</span>
      </Link>
    </div>
    <div class="bg-white rounded-md shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <tr class="text-left font-bold">
          <th class="pb-4 pt-6 px-6">Name</th>
          <th class="pb-4 pt-6 px-6">City</th>
          <th class="pb-4 pt-6 px-6">Email</th>
          <th class="pb-4 pt-6 px-6" colspan="2">Phone</th>
        </tr>
        <tr v-for="customer in customers.data" :key="customer.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
          <td class="border-t">
            <Link class="flex items-center px-6 py-4 focus:text-indigo-500" :href="`/customers/${customer.id}/edit`">
              {{ customer.first_name + " " +customer.last_name }}
              <icon v-if="customer.deleted_at" name="trash" class="flex-shrink-0 ml-2 w-3 h-3 fill-gray-400" />
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4" :href="`/customers/${customer.id}/edit`" tabindex="-1">
              {{ customer.email }}
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4" :href="`/customers/${customer.id}/edit`" tabindex="-1">
              {{ customer.city }}
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4" :href="`/customers/${customer.id}/edit`" tabindex="-1">
              {{ customer.phone }}
            </Link>
          </td>
          <td class="w-px border-t">
            <Link class="flex items-center px-4" :href="`/customers/${customer.id}/edit`" tabindex="-1">
              <icon name="cheveron-right" class="block w-6 h-6 fill-gray-400" />
            </Link>
          </td>
        </tr>
        <tr v-if="customers.data.length === 0">
          <td class="px-6 py-4 border-t" colspan="4">No Customer found.</td>
        </tr>
      </table>
    </div>
    <pagination class="mt-6" :links="customers.links" />
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/inertia-vue3'
import Icon from '@/Shared/Icon'
import pickBy from 'lodash/pickBy'
import Layout from '@/Shared/Layout'
import throttle from 'lodash/throttle'
import Pagination from '@/Shared/Pagination'

export default {
  components: {
    Head,
    Icon,
    Link,
    Pagination,
  },
  layout: Layout,
  props: {
    customers: Object,
  },
  watch: {
    form: {
      deep: true,
      handler: throttle(function () {
        this.$inertia.get('/customers', pickBy(this.form), { preserveState: true })
      }, 150),
    },
  },
}
</script>
