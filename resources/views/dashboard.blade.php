@extends('layout')
@section('title', 'Dashboard')
@section('content')
<!-- Content wrapper -->
<div class="content-wrapper">
  <!-- Content -->
  <div class="container-xxl flex-grow-1 container-p-y">
    <h2>Dashboard</h2>

    <div class="row">
      <div class="col-lg-8 col-md-12 order-1">
        <div class="row mb-4">
          <div class="col-lg-6 col-md-12 mb-4">
            <div class="card">
              <div class="card-body">
                <div class="card-title d-flex align-items-start justify-content-between">
                  <span class="badge bg-label-warning rounded-pill">Total Income</span>
                </div>
                <h3 class="card-title mb-2">₱ {{ number_format($totalIncome * .2, 2) }}</h3>
              </div>
            </div>
          </div>
          <div class="col-lg-6 col-md-12 mb-4">
            <div class="card">
              <div class="card-body">
                <div class="card-title d-flex align-items-start justify-content-between">
                  <span class="badge bg-label-warning rounded-pill">Total Sales </span>
                  <div class="dropdown">
                    <button class="btn p-0" type="button" id="cardOpt6" data-bs-toggle="dropdown" aria-haspopup="true"
                      aria-expanded="false">
                      <i class="bx bx-dots-vertical-rounded"></i>
                    </button>
                  </div>
                </div>

                <h3 class="card-title text-nowrap mb-1">{{$totalSales}}</h3>

              </div>
            </div>
          </div>

          <div class="col-lg-6 col-md-12 mb-4">
            <div class="card">
              <div class="card-body">
                <div class="card-title d-flex align-items-start justify-content-between">
                  <span class="badge bg-label-warning rounded-pill">Total Items</span>
                  <div class="dropdown">
                    <button class="btn p-0" type="button" id="cardOpt6" data-bs-toggle="dropdown" aria-haspopup="true"
                      aria-expanded="false">
                      <i class="bx bx-dots-vertical-rounded"></i>
                    </button>

                  </div>
                </div>

                <h3 class="card-title text-nowrap mb-1">{{$totalItems}}</h3>

              </div>
            </div>
          </div>
        </div>

        <!-- Total Revenue -->
        <div class="card mb-4">
          <div class="row row-bordered g-0">
            <div class="col-md-8">
              <h5 class="card-header m-0 me-2 pb-3">Total Revenue</h5>
              <div id="totalRevenueChart" class="px-2"></div>
            </div>
            <div class="col-md-4">
              <div class="card-body">
                <div class="text-center">
                  <div class="dropdown">
                    <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" id="growthReportId"
                      data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      {{ $currentYear }} <!-- Display currently selected year -->
                    </button>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="growthReportId">
                      @foreach ($years as $year) <!-- Loop through available years -->
                        <a class="dropdown-item" href="{{ route('dashboard', ['year' => $year]) }}">{{ $year }}</a>
                      @endforeach
                    </div>
                  </div>
                </div>
              </div>

              <div class="d-flex px-xxl-4 px-lg-2 p-4 gap-xxl-3 gap-lg-1 gap-3 justify-content-between">
                <div class="d-flex">
                  <div class="me-2">
                    <span class="badge bg-label-primary p-2"><i class="bx bx-dollar text-primary"></i></span>
                  </div>
                  <div class="d-flex flex-column">
                    <small>{{ $currentYear }}</small> <!-- Display current year -->
                    <h6 class="mb-0">₱{{ number_format($currentRevenue, 2) }}</h6> <!-- Display current revenue -->
                  </div>
                </div>
                <div class="d-flex">
                  <div class="me-2">
                    <span class="badge bg-label-info p-2"><i class="bx bx-wallet text-info"></i></span>
                  </div>
                  <div class="d-flex flex-column">
                    <small>{{ $previousYear }}</small> <!-- Display previous year -->
                    <h6 class="mb-0">₱{{ number_format($previousRevenue, 2) }}</h6> <!-- Display previous revenue -->
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /Total Revenue -->
      </div>

      <!-- Most Selling Items on the right side -->
      <div class="col-lg-4 col-md-12 order-2 mb-4"> <!-- Move this card to the right side -->
        <div class="card h-100">
          <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="card-title m-0 me-2 badge bg-label-primary rounded-pill">Most Selling Items</h5>
            <div class="dropdown">
              <button class="btn p-0" type="button" id="paymentID" data-bs-toggle="dropdown" aria-haspopup="true"
                aria-expanded="false">
                <i class="bx bx-dots-vertical-rounded"></i>
              </button>
            </div>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table datanew">
                <thead>
                  <tr>
                    <th>Item Name</th>
                    <th>Total Sales</th>
                    <th>Last Sold Date</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($mostSellingItems as $item)
                    <tr>
                      <td>{{ $item->item ? $item->item->itemName : 'N/A' }}</td>
                      <td>₱ {{$item->sales }}</td>
                      <td>{{ \Carbon\Carbon::parse($item->last_sold_date)->locale('en_PH')->isoFormat('MMMM D, YYYY') }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <!-- /Most Selling Items -->
    </div>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", function () {
      // Data for the chart
      var options = {
        chart: {
          type: 'bar',
          height: 350,
          toolbar: {
            show: false
          }
        },
        series: [{
          name: 'Revenue',
          data: [32500, 41200, 58000, 47000] // Example data for 2022, 2021, 2020, 2019
        }],
        xaxis: {
          categories: ['2022', '2021', '2020', '2019'],
          title: {
            text: 'Years'
          }
        },
        yaxis: {
          title: {
            text: 'Revenue in ₱'
          }
        },
        dataLabels: {
          enabled: true,
        },
        fill: {
          opacity: 1,
        },
        colors: ['#008FFB'],
      };

      // Render the chart
      var chart = new ApexCharts(document.querySelector("#totalRevenueChart"), options);
      chart.render();
    });
  </script>

@endsection
