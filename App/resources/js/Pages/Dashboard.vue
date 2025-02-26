<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

import { Badge } from '@/Components/ui/badge';
import { Card, CardContent, CardHeader, CardTitle } from '@/Components/ui/card';
import { DonutChart } from '@/Components/ui/chart-donut';
import { BarChart } from '@/Components/ui/chart-bar';
import { User, Users, UserCheck, UserX } from 'lucide-vue-next';

const { countEnrollees, countAcceptedEnrollees, countRejectedEnrollees } =
  defineProps({
    countEnrollees: Number,
    countAcceptedEnrollees: Number,
    countRejectedEnrollees: Number,
  });

const donutChartData = [
  {
    name: 'Acceptés',
    total: countAcceptedEnrollees,
  },
  {
    name: 'En Attende',
    total: countEnrollees - countAcceptedEnrollees - countRejectedEnrollees,
  },
  {
    name: 'Refusés',
    total: countRejectedEnrollees,
  },
];

const barChartData = [
  {
    name: 'Francais',
    Acceptés: Math.floor(Math.random() * 2000) + 500,
    EnAttente: Math.floor(Math.random() * 2000) + 500,
    Refusés: Math.floor(Math.random() * 2000) + 500,
  },
  {
    name: 'Englais',
    Acceptés: Math.floor(Math.random() * 2000) + 500,
    EnAttente: Math.floor(Math.random() * 2000) + 500,
    Refusés: Math.floor(Math.random() * 2000) + 500,
  },
  {
    name: 'Programmation',
    Acceptés: Math.floor(Math.random() * 2000) + 500,
    EnAttente: Math.floor(Math.random() * 2000) + 500,
    Refusés: Math.floor(Math.random() * 2000) + 500,
  },
  {
    name: 'Soft Skills',
    Acceptés: Math.floor(Math.random() * 2000) + 500,
    EnAttente: Math.floor(Math.random() * 2000) + 500,
    Refusés: Math.floor(Math.random() * 2000) + 500,
  },
  {
    name: 'Travail en équipe',
    Acceptés: Math.floor(Math.random() * 2000) + 500,
    EnAttente: Math.floor(Math.random() * 2000) + 500,
    Refusés: Math.floor(Math.random() * 2000) + 500,
  },
];
</script>

<template>
  <AuthenticatedLayout>
    <div class="grid gap-4 md:grid-cols-2 md:gap-8 lg:grid-cols-4">
      <Card
        class="elay-50 duration-400 bg-blue-500 text-white shadow-md transition hover:bg-blue-600 hover:text-gray-200 hover:shadow-lg"
      >
        <CardHeader
          class="flex flex-row items-center justify-between space-y-0 pb-2"
        >
          <CardTitle class="text-4xl font-bold">
            {{ countEnrollees }}
          </CardTitle>
          <Users class="h-12 w-12" />
        </CardHeader>
        <CardContent>
          <span class="text-xl font-bold"> Les Inscrits </span>
        </CardContent>
      </Card>
      <Card
        class="delay-50 duration-400 bg-green-500 text-white shadow-md transition hover:bg-green-600 hover:text-gray-300 hover:shadow-lg"
      >
        <CardHeader
          class="flex flex-row items-center justify-between space-y-0 pb-2"
        >
          <CardTitle class="text-4xl font-bold">
            {{ countAcceptedEnrollees }}
          </CardTitle>
          <UserCheck class="h-12 w-12" />
        </CardHeader>
        <CardContent>
          <span class="text-xl font-bold"> Acceptés </span>
        </CardContent>
      </Card>
      <Card
        class="delay-50 duration-400 bg-orange-400 text-white shadow-md transition hover:bg-orange-500 hover:text-gray-300 hover:shadow-lg"
      >
        <CardHeader
          class="flex flex-row items-center justify-between space-y-0 pb-2"
        >
          <CardTitle class="text-4xl font-bold"> 200 </CardTitle>
          <User class="h-12 w-12" />
        </CardHeader>
        <CardContent>
          <span class="text-xl font-bold"> En Attende </span>
        </CardContent>
      </Card>
      <Card
        class="delay-50 duration-400 bg-red-500 text-white shadow-md transition hover:bg-red-600 hover:text-gray-300 hover:shadow-lg"
      >
        <CardHeader
          class="flex flex-row items-center justify-between space-y-0 pb-2"
        >
          <CardTitle class="text-4xl font-bold">
            {{ countRejectedEnrollees }}
          </CardTitle>
          <UserX class="h-12 w-12" />
        </CardHeader>
        <CardContent>
          <span class="text-xl font-bold"> Refusés </span>
        </CardContent>
      </Card>
    </div>
    <div class="grid w-full grid-cols-2 gap-4">
      <Card class="col-span-1">
        <CardHeader class="flex flex-row items-center">
          <div class="flex w-full flex-col gap-4">
            <CardTitle class="text-3xl font-bold"> </CardTitle>
          </div>
        </CardHeader>
        <CardContent>
          <div class="mb-3 flex justify-center gap-4">
            <Badge class="bg-green-500 hover:bg-green-600"> Acceptés </Badge>
            <Badge class="bg-orange-400 hover:bg-orange-500">
              En Attende
            </Badge>
            <Badge class="bg-red-500 hover:bg-red-600"> Refusés </Badge>
          </div>
          <DonutChart
            index="name"
            class="w-75 h-75"
            :showLegend="false"
            :category="'total'"
            :data="donutChartData"
            :colors="['#fb923c', '#22c55e', '#e74c3c']"
          />
        </CardContent>
      </Card>
      <Card class="col-span-1">
        <CardHeader>
          <CardTitle> </CardTitle>
        </CardHeader>
        <CardContent class="grid gap-8">
          <BarChart
            :data="barChartData"
            index="name"
            :colors="['#fb923c', '#22c55e', '#e74c3c']"
            :categories="['Acceptés', 'EnAttente', 'Refusés']"
            :y-formatter="
              (tick, i) => {
                return typeof tick === 'number'
                  ? `$ ${new Intl.NumberFormat('us').format(tick).toString()}`
                  : '';
              }
            "
          />
        </CardContent>
      </Card>
    </div>
  </AuthenticatedLayout>
</template>
