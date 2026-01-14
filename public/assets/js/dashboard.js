$(function () {
    let baseUrl = "/admin/dashboard";
    let filters = { from: null, to: null };
    let salesChart;
    let charts = {
        salesOverTimeChart: null,
        newCustomersChart: null,
        paymentMethodChart: null,
        topProductsChart: null,
    };

    // INIT
    initDateRangePicker();
    loadAllDashboardData();

    function initDateRangePicker() {
        const $input = $("#dashboardDateRange");

        const start = moment().subtract(6, "days");
        const end = moment();

        function updateDisplay(start, end) {
            $input.val(
                start.format("MMM D, YYYY") + " - " + end.format("MMM D, YYYY")
            );
            filters.from = start.format("YYYY-MM-DD");
            filters.to = end.format("YYYY-MM-DD");
            loadAllDashboardData();
        }

        $input.daterangepicker(
            {
                startDate: start,
                endDate: end,
                ranges: {
                    Today: [moment(), moment()],
                    Yesterday: [
                        moment().subtract(1, "days"),
                        moment().subtract(1, "days"),
                    ],
                    "Last 7 Days": [moment().subtract(6, "days"), moment()],
                    "Last 30 Days": [moment().subtract(29, "days"), moment()],
                    "This Month": [
                        moment().startOf("month"),
                        moment().endOf("month"),
                    ],
                    "Last Month": [
                        moment().subtract(1, "month").startOf("month"),
                        moment().subtract(1, "month").endOf("month"),
                    ],
                },
                opens: "left",
                autoUpdateInput: false,
                locale: {
                    format: "YYYY-MM-DD",
                    cancelLabel: "Clear",
                },
            },
            updateDisplay
        );

        // Initial display
        updateDisplay(start, end);
    }

    function buildQueryParams() {
        return `?from=${filters.from}&to=${filters.to}`;
    }

    function loadAllDashboardData() {
        loadDashboardMetrics();
        loadSalesChart();
        loadRecentOrders();
        loadTopProductsChart();
        loadNewCustomersChart();
        loadPaymentMethodChart();
    }

    function loadDashboardMetrics() {
        $.ajax({
            url: baseUrl + "/metrics" + buildQueryParams(),
            method: "GET",
            beforeSend: function () {
                $("#total-sales").text("...");
                $("#total-orders").text("...");
                $("#total-customers").text("...");
                $("#total-products").text("...");
                $("#pending-returns").text("...");
            },
            success: function (res) {
                $("#total-sales").text('AED ' + res.total_sales.toFixed(2));
                $("#total-orders").text(res.total_orders);
                $("#total-customers").text(res.total_customers);
                $("#total-products").text(res.total_products);
                $("#pending-returns").text(res.pending_returns);
            },
        });
    }

    function loadSalesChart() {
        $.get(baseUrl + "/sales-chart" + buildQueryParams(), function (res) {
            const ctx = document.getElementById("salesChart").getContext("2d");

            if (charts.salesOverTimeChart) charts.salesOverTimeChart.destroy();

            charts.salesOverTimeChart = new Chart(ctx, {
                type: "line",
                data: {
                    labels: res.labels,
                    datasets: [
                        {
                            label: "Total Sales",
                            data: res.data,
                            borderColor: "#007bff",
                            backgroundColor: "rgba(0,123,255,0.1)",
                            tension: 0.3,
                            fill: true,
                        },
                    ],
                },
                options: {
                    responsive: true,
                    scales: { y: { beginAtZero: true } },
                },
            });
        });
    }

    function loadRecentOrders() {
        $.get(
            baseUrl + "/recent-orders" + buildQueryParams(),
            function (orders) {
                const tbody = $("#recentOrdersTable tbody");
                tbody.empty();

                orders.forEach((order) => {
                    tbody.append(`
                    <tr>
                      <td>${order.reference_number}</td>
                      <td>${order.customer}</td>
                      <td>${order.currency} ${parseFloat(order.total).toFixed(
                        2
                    )}</td>
                      <td><span class="badge bg-${order.payment_status === "Paid"
                            ? "success"
                            : "warning"
                        }">${order.payment_status}</span></td>
                      <td>${order.date}</td>
                    </tr>`);
                });

                $("#ordersLoader").remove();
                $("#recentOrdersTable").removeClass("d-none");
            }
        );
    }

    function loadTopProductsChart() {
        $.get(baseUrl + "/top-products" + buildQueryParams(), function (data) {
            const labels = data.map((d) => d.label);
            const values = data.map((d) => d.qty);
            const ctx = document
                .getElementById("topProductsChart")
                .getContext("2d");

            if (charts.topProductsChart) charts.topProductsChart.destroy(); // 💥

            charts.topProductsChart = new Chart(ctx, {
                type: "bar",
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: "Quantity Sold",
                            data: values,
                            backgroundColor: "rgba(54, 162, 235, 0.7)",
                            borderColor: "rgba(54, 162, 235, 1)",
                            borderWidth: 1,
                        },
                    ],
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { precision: 0 },
                        },
                    },
                },
            });

            $("#topProductsLoader").remove();
            $("#topProductsChart").removeClass("d-none");
        });
    }


    function loadNewCustomersChart() {
        $.get(baseUrl + "/new-customers" + buildQueryParams(), function (data) {
            const labels = data.map((d) => d.date);
            const counts = data.map((d) => d.count);
            const ctx = document
                .getElementById("newCustomersChart")
                .getContext("2d");

            if (charts.newCustomersChart) charts.newCustomersChart.destroy(); // 💥

            charts.newCustomersChart = new Chart(ctx, {
                type: "bar",
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: "New Signups",
                            data: counts,
                            backgroundColor: "rgba(54, 162, 235, 0.6)",
                            borderColor: "rgba(54, 162, 235, 1)",
                            borderWidth: 1,
                        },
                    ],
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { stepSize: 1 },
                        },
                    },
                },
            });

            $("#newCustomersLoader").remove();
            $("#newCustomersChart").removeClass("d-none");
        });
    }

    function loadPaymentMethodChart() {
        $.get(baseUrl + "/payment-methods" + buildQueryParams(), function (data) {
            const labels = data.map((row) => row.payment_method.toUpperCase());
            const counts = data.map((row) => row.count);
            const ctx = document
                .getElementById("paymentMethodChart")
                .getContext("2d");

            if (charts.paymentMethodChart) charts.paymentMethodChart.destroy(); // 💥

            charts.paymentMethodChart = new Chart(ctx, {
                type: "pie",
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: "Orders by Payment Method",
                            data: counts,
                            backgroundColor: ["#36A2EB", "#FF6384", "#FFCE56"],
                            borderWidth: 1,
                        },
                    ],
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: "bottom" },
                    },
                },
            });

            $("#paymentMethodLoader").remove();
            $("#paymentMethodChart").removeClass("d-none");
        });
    }
});
