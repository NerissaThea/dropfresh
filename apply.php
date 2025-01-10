<div class="section-title">
    <div class="title-hr"></div>
    <h1>Job Application Form</h1>
    <div class="title-hr"></div>
</div>

<div id="alert" class="alert">
    <strong>Success!</strong> Your job application has been submitted.
</div>

<main class="form-content">
    <h2>Please carefully check the information filled in before sending</h2>
    <form method="post" action="processEOI.php" accept-charset="UTF-8">

        <p>
            <label for="jobref"><span>*</span>Job Reference Number:<br><span>(5 alphanumeric characters)</span></label>
            <input class="forminput" type="text" id="jobref" name="jobref" pattern="[A-Za-z0-9]{5}" required>
        </p>

        <p>
            <label class="form-name" for="firstname"><span>*</span>First Name:<br><span>(max 20 alpha characters)</span></label>
            <input class="forminput" type="text" id="firstname" name="firstname" maxlength="20" required>

            <label for="lastname"><span>*</span>Last Name:<br><span>(max 20 alpha characters)</span></label>
            <input class="forminput" type="text" id="lastname" name="lastname" maxlength="20" required>
        </p>

        <p>
            <label for="date"><span>*</span>Date of Birth:</label>
            <input class="forminput" type="date" id="date" name="date" required>
        </p>

        <p>
            <label for="gender"><span>*</span>Gender:</label>
            <select id="gender" name="gender" required>
                <option value="">Please select</option>
                <option value="male">Male</option>
                <option value="female">Female</option>
            </select>
        </p>

        <p>
            <label for="address"><span>*</span>Street Address:<br><span>(max 40 characters)</span></label>
            <input class="forminput" type="text" id="address" name="address" maxlength="40" required>

            <label for="suburb"><span>*</span>Suburb/Town:<br><span>(max 40 characters)</span></label>
            <input class="forminput" type="text" id="suburb" name="suburb" maxlength="40" required>
        </p>

        <p>
            <label for="state"><span>*</span>State:</label>
            <select id="state" name="state" required>
                <option value="">Please select</option>
                <option value="VIC">VIC</option>
                <option value="NSW">NSW</option>
                <option value="QLD">QLD</option>
                <option value="NT">NT</option>
                <option value="WA">WA</option>
                <option value="SA">SA</option>
                <option value="TAS">TAS</option>
                <option value="ACT">ACT</option>
            </select>

            <label for="postcode"><span>*</span>Postcode:<br><span>(exactly 4 digits)</span></label>
            <input class="forminput" type="text" id="postcode" name="postcode" pattern="[0-9]{4}" required>
        </p>

        <p>
            <label for="email"><span>*</span>Email:</label>
            <input class="forminput" type="email" id="email" name="email" required>

            <label for="phone"><span>*</span>Phone Number:<br><span>(8 to 12 digits)</span></label>
            <input class="forminput" type="tel" id="phone" name="phone" pattern="[0-9]{8,12}" required>
        </p>

        <p class="jobsoption">
            <b><span>*</span>Skills:</b>

            <input class="jobsoptioncheckbox" type="checkbox" id="skill1" name="skills[]" value="HTML">
            <label class="jobsoptionlabel" for="skill1">HTML</label>

            <input class="jobsoptioncheckbox" type="checkbox" id="skill2" name="skills[]" value="CSS">
            <label class="jobsoptionlabel" for="skill2">CSS</label>

            <input class="jobsoptioncheckbox" type="checkbox" id="skill3" name="skills[]" value="JavaScript">
            <label class="jobsoptionlabel" for="skill3">JavaScript</label>

            <input class="jobsoptioncheckbox" type="checkbox" id="skill4" name="skills[]" value="Other">
            <label class="jobsoptionlabel labelcheck" for="skill4">Other skills...</label>
        </p>

        <p id="otherSkillsSection" class="hidden">
            <label for="otherskills">Other Skills:</label>
            <textarea id="otherskills" name="otherskills" placeholder="Enter here"></textarea>
        </p>

        <button type="submit" class="section-5 button apply">APPLY NOW</button>
    </form>
</main>
