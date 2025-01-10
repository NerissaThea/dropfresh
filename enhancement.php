<h1>Enhancement 1</h1>
<div class="containerenhancement">
<p class="enhancement">
   <strong> This is the auto hover feature. This feature is intended to attract users to the
    information I want to highlight.</strong> <br>
    For this feature I use animation: autohover and autohover keyframes.
    Autohover keyframes are used to format the display for it when autohover.
    And in this button I have added a stop state when the mouse is inserted.
    Besides, I want users to be more certain about their choice, I have set the font
    size to be larger when hovering to clearly see the difference.
</p>

<div class="join enhancementjoin">
    <div class="button" id="hover">Join "Stay hydrated" community</div>
</div>

<p class="enhancement">
<strong>This is a feature when clicking 'view more', a new table will be displayed right 
on the interface without having to redirect to another page.</strong> <br> This helps users 
save time, they can directly view the jobs they want to apply for.  
In terms of code that implements this feature, in the default state, the website 
hides the content of the job description, this job description has an id in the 
general class. Then I use href="#id that I placed in 'view more'. The most 
important part is the css so that the hidden class displays:block when targeted. 
Besides, I also have a close button at the top, when I click on it, I want it to 
display the jobs.html page again and target the id of that job. If it's just a simple 
link of the a tag, it will jump to the top of the page and make the user 
experience not good because they has to scroll down again.
</p>
<ul  class="join enhancementjoin">
    <li class="jobs" id="close1">
        <div class="jobs-mn">
            <img src="images/logo/logover2.svg" alt="DropFresh logo1">
            <div class="nd-jobs">
                <h2>DATA SCIENTIST</h2>
                <div class="jobs-1">
                    <ul>
                        <li>Location: Ho Chi Minh</li>
                        <li>Number of position: 1</li>
                    </ul>
                </div>
                <div class="jobs-1">
                    <ul>
                        <li>Posted: 16/02/2014</li>
                        <li>Deadline to apply: 31/03/2024</li>
                    </ul>
                </div>
            </div>
        </div>

        <a href="#job-info" class="block">View more</a>

    </li>
</ul>
<section class="infoPanel" id="job-info">
    <a href="#close1"><i class="fa-solid fa-square-xmark">close</i></a>

    <div class="section-1">
        <h3>POSITION TILE: Data Scientist</h3>
        <h3>POSITION ID: DF01D</h3>
    </div>

    <div class="section-2">
        <h3>JOB DESCRIPTIION</h3>

        <p>We are looking for an experienced Data Scientist to analyze data and develop models to extract
            meaningful insights for our business.</p>
    </div>

    <h3>JOB BENEFIT</h3>
    <div class="job-benefit">
        <ul class="jobs-section-1">
            <li><i class="fa-solid fa-laptop"></i> Laptop</li>
            <li><i class="fa-solid fa-suitcase-medical"></i> Insurance </li>
            <li><i class="fa-solid fa-plane-departure"></i> Travel opportunities</li>
            <li><i class="fa-solid fa-money-bills"></i> Allowances</li>
            <li><i class="fa-solid fa-shirt"></i> Uniform</li>
            <li><i class="fa-solid fa-dollar-sign"></i> Incentive bonus</li>
        </ul>
        <ul class="jobs-section-1">
            <li><i class="fa-solid fa-graduation-cap"></i> Training & Development</li>
            <li><i class="fa-solid fa-arrow-up-right-dots"></i> Salary review</li>
            <li><i class="fa-solid fa-user-doctor"></i> Health checkup</li>
            <li><i class="fa-solid fa-money-bills"></i> Seniority Allowance</li>
            <li><i class="fa-solid fa-briefcase"></i> Annual Leave</li>
            <li><i class="fa-solid fa-heart-circle-plus"></i> Health checkup</li>
        </ul>
    </div>
    <div class="section-3">
        <h3>SALARY RANGE:</h3>
        <p>$90.000 - $120,000</p>
    </div>
    <div class="section-3">
        <h3>REPORTS TO:</h3>
        <p> Analytics Manager</p>
    </div>


    <div class="section-4">
        <h3>KEY RESPONSIBILITIES</h3>

        <ul>
            <li>Mine data from databases and compile relevant information for analysis</li>
            <li>Develop and implement statistical models, machine learning algorithms, and other advanced techniques
            </li>
            <li>Visualize and present data findings to stakeholders</li>
            <li>Identify trends and patterns in data sets</li>
            <li>Make recommendations for process improvements based on analysis</li>
            <li>Maintain data systems and databases</li>
        </ul>
    </div>

    <div class="section-4">
        <h3>REQUIRED SKILLS AND QUALIFICATIONS</h3>

        <h4>Essential:</h4>

        <ul>
            <li>Master's degree in Data Science, Statistics or related field</li>
            <li>3 years experience in data science role</li>
            <li>Expertise in Python, R, SQL, statistical modeling, machine learning</li>
            <li>Strong analytical skills with ability to communicate complex insights</li>

        </ul>
    </div>

    <div class="section-4">
        <h4>Preferable:</h4>
        <ul>
            <li>Knowledge of visualization tools like Tableau, PowerBI</li>
            <li>Experience in consumer goods or beverage industry</li>
            <li>Cloud platform knowledge (AWS, Azure)</li>
        </ul>
    </div>
    <div class="section-5 button"> <a href="index.php?pg=apply">APPLY NOW</a> <i class="fa-solid fa-angles-right"></i>
    </div>

</section>
</div>