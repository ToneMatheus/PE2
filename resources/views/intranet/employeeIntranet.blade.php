<style>
    .card:hover{
        box-shadow: 0px 0px 5px rgb(188, 187, 187);
    }
</style>

<script>
  function check(index) {
      var checkBox = document.getElementById('checkBox' + index);
      var value = document.getElementById('check' + index);
      var valueText = value.innerText;

      if (checkBox.checked) {
          value.innerHTML = "<del>" + valueText + "</del>";
      } else {
          value.innerHTML = valueText; // Changed from innerText to innerHTML
      }
  }
</script>

<div class="intranet" style="margin: 20px 30px 30px 0px; display: flex; justify-content: space-between;">
    <div class="left-side">
        <div class="card" style="display: flex; width: 800px; border-radius: 5px; height: 180px; padding-left: 25px">
            <img class="card-img" src="/images/tropical-vacation.jpg" alt="Card image" style="width: 35%; border-top-left-radius: 5px; border-bottom-left-radius: 5px;">
            <div class="card-body" style="background-color: rgb(242, 240, 240); padding: 35px; border-top-right-radius: 5px; border-bottom-right-radius: 5px;">
              <h4 class="card-title h4" style=" font-weight: 900; font-size: 18px">Planning to take time off?</h4>
              <p class="card-text text-sm" style="padding: 8px 0px;">Check out all these amazing destinations where most of our employees travel to</p>
              <a href="https://be.trip.com/?allianceid=742331&sid=1621983&ppcid=ckid-5019962950_adid-83700721906436_akid-kwd-83701490046425_adgid-1339206892823144&utm_source=bing&utm_medium=cpc&utm_campaign=602829494&msclkid=9e22f95334211d8218d39f7862722a6a&locale=en-be" class="text-blue-500">Read more</a>
            </div>
        </div>

        <div class="card" style="display: flex; width: 800px; border-radius: 5px; margin-top: 20px; height: 180px; padding-left: 25px">
            <img class="card-img" src="/images/greenpowerimage.jpg" alt="Card image" style="width: 35%; border-top-left-radius: 5px; border-bottom-left-radius: 5px;">
            <div class="card-body" style="background-color: rgb(242, 240, 240); padding: 35px; border-top-right-radius: 5px; border-bottom-right-radius: 5px;">
              <h4 class="card-title h4" style=" font-weight: 900; font-size: 18px">Heading towards green energy</h4>
              <p class="card-text text-sm" style="padding: 8px 0px;">Our company's energy to be 100% green by 2035. What is this green energy?</p>
              <a href="https://www.nationalgrid.com/stories/energy-explained/what-is-green-energy" class="text-blue-500">Read more</a>
            </div>
        </div>

        <div class="card" style="display: flex; width: 800px; border-radius: 5px; margin-top: 20px; height: 180px; padding-left: 25px">
            <img class="card-img" id="img" src="/images/benefit.png" alt="Card image" style="width: 35%; border-top-left-radius: 5px; border-bottom-left-radius: 5px;">
            <div class="card-body" style="background-color: rgb(242, 240, 240); padding: 35px; border-top-right-radius: 5px; border-bottom-right-radius: 5px;">
              <h4 class="card-title h4" style=" font-weight: 900; font-size: 18px">Benefits and compensation</h4>
              <p class="card-text text-sm" style="padding: 8px 0px;">We care about our workers. See the many benefits we provide as a company</p>
              <a href="{{route('employeeBenefits')}}" class="text-blue-500">Visit</a>
            </div>
        </div>
    </div>
    
    <div>
      <a href="#" class="block">
        <div class="tw=block flex flex-col items-center justify-center bg-gray-200 dark:bg-gray-700 rounded-lg shadow p-4" style="margin-left: 40px">
            <span class="text-black-700 hover:text-blue-700 dark:text-white dark:hover:text-gray-400 mb-2" style="font-size: 18px; font-weight: 900;">Daily tasks</span>
            <ul class="list-group" style="width: 280px">
              <li class="list-group-item">
                  <input class="form-check-input me-1" type="checkbox" value="" aria-label="..." id="checkBox1" onclick="check(1)">
                  <span id="check1">First checkbox</span>
              </li>
              <li class="list-group-item">
                  <input class="form-check-input me-1" type="checkbox" value="" aria-label="..." id="checkBox2" onclick="check(2)">
                  <span id="check2">Second checkbox</span>
              </li>
              <li class="list-group-item">
                <input class="form-check-input me-1" type="checkbox" value="" aria-label="..." id="checkBox3" onclick="check(3)">
                <span id="check3">Third checkbox</span>
            </li>
            </ul>
              
              <div style="display: flex; justify-content: space-between; margin-top: 30px">
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" style="margin-right: 20px">
                    Add task
                  </button>
                  <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Clear tasks
                  </button>
              </div>
        </div>
    </a>

      <diV class="tw=block flex flex-col items-center justify-center bg-gray-200 dark:bg-gray-700 rounded-lg shadow p-4" style="margin-left: 40px; margin-top: 30px">
        <h3 class="card-title h3" style=" font-weight: 900; font-size: 18px">Weekly reports</h3>
        <p>What have you done this week?</p>
        <img src='/images/weekly-report.png' style="width: 200px"/>
        <p>Click <a href="{{ route('weeklyActivity') }}" class="text-blue-500" style=" font-weight: 900;">here</a> to submit weekly report</p>
      </diV>
    </div>
</div>
