$.getJSON("/api/v1/listings/total/average/", function(data) {
  console.log(data.total_average_listings);
});

$.getJSON("/api/v1/deals/", function(data) {
  console.log(data.deals);
});
