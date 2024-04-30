<style>
    .card:hover{
        box-shadow: 0px 0px 5px rgb(188, 187, 187);
    }
</style>

<div class="intranet" style="margin: 20px 30px 30px 0px; display: flex; justify-content: space-between;">
    <div class="left-side">
        <div class="card" style="display: flex; width: 600px; border-radius: 5px">
            <img class="card-img" src="/images/tropical-vacation.jpg" alt="Card image" style="width: 40%; border-top-left-radius: 5px; border-bottom-left-radius: 5px;">
            <div class="card-body" style="background-color: rgb(242, 240, 240); padding: 35px; border-top-right-radius: 5px; border-bottom-right-radius: 5px;">
              <h4 class="card-title h4" style=" font-weight: 900; font-size: 18px">Planning to take time off?</h4>
              <p class="card-text text-sm" style="padding: 8px 0px;">Check out all these amazing destinations where most of our employees travel to</p>
              <a href="https://be.trip.com/?allianceid=742331&sid=1621983&ppcid=ckid-5019962950_adid-83700721906436_akid-kwd-83701490046425_adgid-1339206892823144&utm_source=bing&utm_medium=cpc&utm_campaign=602829494&msclkid=9e22f95334211d8218d39f7862722a6a&locale=en-be" class="text-blue-500">Read more</a>
            </div>
        </div>

        <div class="card" style="display: flex; width: 600px; max-width: 600px; border-radius: 5px; margin-top: 20px;">
            <img class="card-img" src="/images/greenpowerimage.jpg" alt="Card image" style="width: 40%; border-top-left-radius: 5px; border-bottom-left-radius: 5px;">
            <div class="card-body" style="background-color: rgb(242, 240, 240); padding: 35px; border-top-right-radius: 5px; border-bottom-right-radius: 5px;">
              <h4 class="card-title h4" style=" font-weight: 900; font-size: 18px">Heading towards green energy</h4>
              <p class="card-text text-sm" style="padding: 8px 0px;">Our company's energy to be 100% green by 2035</p>
              <a href="https://www.nationalgrid.com/stories/energy-explained/what-is-green-energy" class="text-blue-500">Read more</a>
            </div>
        </div>

        <div class="card" style="display: flex; width: 600px; max-width: 600px; border-radius: 5px; margin-top: 20px;">
            <img class="card-img" src="/images/care.png" alt="Card image" style="width: 40%; border-top-left-radius: 5px; border-bottom-left-radius: 5px;">
            <div class="card-body" style="background-color: rgb(242, 240, 240); padding: 35px; border-top-right-radius: 5px; border-bottom-right-radius: 5px;">
              <h4 class="card-title h4" style=" font-weight: 900; font-size: 18px">Benefits and compensation</h4>
              <p class="card-text text-sm" style="padding: 8px 0px;">We care about our workers. See the many benefits we provide as a company</p>
              <a href="{{route('employeeBenefits')}}" class="text-blue-500">Visit</a>
            </div>
        </div>
    </div>
    
    <a href="#" class="block">
        <div class="flex flex-col items-center justify-center bg-gray-200 dark:bg-gray-700 rounded-lg shadow p-4" style="margin-left: 230px">
            <span class="text-black-700 hover:text-blue-700 dark:text-white dark:hover:text-gray-400 mb-2" style="font-size: 18px; font-weight: 900;">To-do</span>
            <p class="text-gray-600 dark:text-gray-400 text-sm">        
                <ul style="width: 300px;">
                <li>Take out trash</li>
                <li>Update customer information</li>
                <li>Clean office</li>
            </ul></p>
        </div>
    </a>

    <div class="flex flex-col items-center justify-center bg-gray-200 dark:bg-gray-700 rounded-lg shadow p-4" style="margin-left: 230px">
        <span class="text-black-700 hover:text-blue-700 dark:text-white dark:hover:text-gray-400 mb-2" style="font-size: 18px; font-weight: 900;">New employees</span>
        <p class="text-gray-600 dark:text-gray-400 text-sm">        
            <ul style="width: 300px;" class="list-none md:list-disc">
            <li>Take out trash</li>
            <li>Update customer information</li>
            <li>Clean office</li>
        </ul></p>
    </div>
</div>