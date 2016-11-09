


function AvgListingViewModel() {
  var self = this;

  self.avgLeadsArr = ko.observableArray();

  self.updateAvgLeads = function(data) {

  self.avgLeadsArr.removeAll();
  $.each(data.total_average_listings, function(region, avg) {
        self.avgLeadsArr.push({
          'region': region,
          'avg': avg
        });
      });
    };


  $.getJSON("/api/v1/listings/total/average/", self.updateAvgLeads)

}

function DealsViewModel() {
  var self = this;

  self.pendingCount = ko.observable();
  self.pendingDollarValue = ko.observable();
  self.settledCount = ko.observable();
  self.settledDollarValue = ko.observable();

  self.updateDeals = function(data) {

    self.pendingCount(data.deals.pending.count);
    self.pendingDollarValue(data.deals.pending.dollar_value);
    self.settledCount(data.deals.settled.count);
    self.settledDollarValue(data.deals.settled.dollar_value);

  };


  $.getJSON("/api/v1/deals/", self.updateDeals)

}


$(document).ready(function() {
  ko.applyBindings(new AvgListingViewModel(),document.getElementById('AvgListings'));
  ko.applyBindings(new DealsViewModel(),document.getElementById('Deals'));
});
