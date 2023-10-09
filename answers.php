<form method="POST" action="submit.php"> <!-- Replace 'submit.php' with your form handling script -->
  <div class="form-group">
    <label for="type">Type</label>
    <input type="text" class="form-control" id="type" name="type" placeholder="Enter Type" required>
  </div>
  <div class="form-group">
    <label for="display_value">Display Value</label>
    <input type="text" class="form-control" id="display_value" name="display_value" placeholder="Enter Display Value" required>
  </div>
  <div class="form-group">
    <label for="system_value">System Value</label>
    <input type="text" class="form-control" id="system_value" name="system_value" placeholder="Enter System Value" required>
  </div>
  <div class="form-group">
    <label for="leads_to">Leads To</label>
    <input type="number" class="form-control" id="leads_to" name="leads_to" placeholder="Enter Leads To" required>
  </div>
  <button type="submit" class="btn btn-primary">Submit</button>
</form>
