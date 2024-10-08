@extends('layout')
@section('title', 'Activity Log')
@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <h1>Activity Log</h1>
               <div class="card">
                  <div class="card-body">
                     <div class="table-top">
                        <div class="search-set">
                           <div class="search-path">
                              <a class="btn btn-filter" id="filter_search">
                              <img src="{{asset('img/icons/search-whites.svg')}}" alt="img">
                              <span><img src="{{asset('img/icons/closes.svg')}}" alt="img"></span>
                              </a>
                           </div>
                           <div class="search-input">
                              <a class="btn btn-searchset"><img src="{{asset('img/icons/search-white.svg')}}" alt="img"></a>
                           </div>
                        </div>
                     </div>
                  
                     <div class="table-responsive">
                        <table class="table  datanew">
                           <thead>
                              <tr>
                                 
                                 <th>NO.</th>
                                 <th>Type</th>
                                 <th>User</th>
                                 <th>Actions</th>
                                 <th>Date</th>
                                 <th>Time</th>
                              </tr>
                           </thead>
                           <tbody>
                              <tr>
                                 <td>01</td>
                                 <td>Inventory Adjustment</td>
                                 <td>Manager</td>
                                 <td>Quantity Adjustment</td>
                                 <td>02/08/24</td>
                                 <td>04:15 PM</td>
                              </tr>
                              <tr>
                                 <td>01</td>
                                 <td>Inventory Adjustment</td>
                                 <td>Manager</td>
                                 <td>Quantity Adjustment</td>
                                 <td>02/08/24</td>
                                 <td>04:15 PM</td>
                              </tr>
                              <tr>
                                 <td>01</td>
                                 <td>Inventory Adjustment</td>
                                 <td>Manager</td>
                                 <td>Quantity Adjustment</td>
                                 <td>02/08/24</td>
                                 <td>04:15 PM</td>
                              </tr>
                              <tr>
                                 <td>01</td>
                                 <td>Inventory Adjustment</td>
                                 <td>Manager</td>
                                 <td>Quantity Adjustment</td>
                                 <td>02/08/24</td>
                                 <td>04:15 PM</td>
                              </tr>
                              <tr>
                                 <td>01</td>
                                 <td>Inventory Adjustment</td>
                                 <td>Manager</td>
                                 <td>Quantity Adjustment</td>
                                 <td>02/08/24</td>
                                 <td>04:15 PM</td>
                              </tr>
                           </tbody>
                        </table>
                     </div>
                  </div>
               </div>
            </div>
         </div>
          <!-- Modal -->
          <div class="modal fade" id="stockCardModal" tabindex="-1" aria-labelledby="stockCardModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="stockCardModalLabel">Inventory Management > Stock Card</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <!-- Item Details Section -->
                            <div class="row mb-3">
                                <div class="col-md-2">
                                    <label for="itemId" class="form-label">Item ID</label>
                                    <input type="text" class="form-control" id="itemId" value="001502" readonly>
                                </div>
                                <div class="col-md-2">
                                    <label for="sku" class="form-label">SKU</label>
                                    <input type="text" class="form-control" id="sku" value="S-ED-250ml" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label for="itemName" class="form-label">Item Name</label>
                                    <input type="text" class="form-control" id="itemName" value="Sting" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label for="companyName" class="form-label">Company Name</label>
                                    <input type="text" class="form-control" id="companyName" value="Pepsi-Cola Products Philippines, Inc. (PCPPI)" readonly>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label for="barcode" class="form-label">Barcode</label>
                                    <input type="text" class="form-control" id="barcode" value="025365894234" readonly>
                                </div>
                                <div class="col-md-3">
                                    <label for="expirationDate" class="form-label">Expiration Date</label>
                                    <input type="text" class="form-control" id="expirationDate" value="05/26/2026" readonly>
                                </div>
                                <div class="col-md-3">
                                    <label for="batch" class="form-label">Batch</label>
                                    <input type="text" class="form-control" id="batch" value="1" readonly>
                                </div>
                                <div class="col-md-3">
                                    <label for="contactPerson" class="form-label">Contact Person</label>
                                    <input type="text" class="form-control" id="contactPerson" value="Mr. Leornero Dasican" readonly>
                                </div>
                            </div>
                            <!-- Inventory Movement Table -->
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Remarks</th>
                                            <th>Quantity-In</th>
                                            <th>Value-In</th>
                                            <th>Quantity-Out</th>
                                            <th>Value-Out</th>
                                            <th>QTY on Hand</th>
                                            <th>Value</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>04/10/2024</td>
                                            <td>Opening Balance</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td>30</td>
                                            <td>₱1,260.00</td>
                                        </tr>
                                        <tr>
                                            <td>04/10/2024</td>
                                            <td>Sales Order</td>
                                            <td></td>
                                            <td></td>
                                            <td>5</td>
                                            <td>₱210.00</td>
                                            <td>25</td>
                                            <td>₱1,050.00</td>
                                        </tr>
                                        <tr>
                                            <td>04/11/2024</td>
                                            <td>Purchase Order</td>
                                            <td>25</td>
                                            <td>₱750.00</td>
                                            <td></td>
                                            <td></td>
                                            <td>50</td>
                                            <td>₱1,800.00</td>
                                        </tr>
                                        <tr>
                                            <td>04/15/2024</td>
                                            <td>Returned</td>
                                            <td>2</td>
                                            <td>₱84.00</td>
                                            <td></td>
                                            <td></td>
                                            <td>52</td>
                                            <td>₱1,884.00</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="movingItemsModal" tabindex="-1" aria-labelledby="movingItemsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="movingItemsModalLabel">Inventory Management > Moving Items</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <!-- Fast Moving Item Section -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h6 class="fw-bold">Fast Moving Item</h6>
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Item Name</th>
                                                    <th>Quantity Sold</th>
                                                    <th>Quantity Planned</th>
                                                    <th>Average Turnover</th>
                                                    <th>Total Sales</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>Young Town Sardine</td>
                                                    <td>225</td>
                                                    <td>500</td>
                                                    <td>1.37%</td>
                                                    <td>₱5,400.00</td>
                                                </tr>
                                                <tr>
                                                    <td>Nestea Apple</td>
                                                    <td>186</td>
                                                    <td>350</td>
                                                    <td>1.31%</td>
                                                    <td>₱5,580.00</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <!-- Slow Moving Item Section -->
                            <div class="row">
                                <div class="col-12">
                                    <h6 class="fw-bold">Slow Moving Item</h6>
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Item Name</th>
                                                    <th>Quantity Sold</th>
                                                    <th>Quantity Planned</th>
                                                    <th>Average Turnover</th>
                                                    <th>Total Sales</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>White Sugar 1Kg.</td>
                                                    <td>12</td>
                                                    <td>150</td>
                                                    <td>1.04%</td>
                                                    <td>₱2,160.00</td>
                                                </tr>
                                                <tr>
                                                    <td>Sting</td>
                                                    <td>8</td>
                                                    <td>250</td>
                                                    <td>1.02%</td>
                                                    <td>₱2,800.00</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
@stop