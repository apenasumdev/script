<section id="install" class="none">

    <form name="installer">
        <div class="form-errors color-red">
        <span class="icon icon-error mr-2"></span>
        Error Here
        </div>

        <div class="form-wrapper">

            <div class="sub-section">
                <h3 class="section-title">License:</h3>

                <div class="form-element mb-20">
                    <div class="col">
                        <label>PURCHASE CODE</label>
                        <span class="tip">
                            Please include your purchase code.
                        </span>
                    </div>

                    <input type="text" name="license" placeholder="xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx" required>
                </div>
            </div>

            <div class="sub-section">
                <h3 class="section-title">Database connection details:</h3>
            
                <div class="form-element mb-20">
                        <div class="col">
                        <label>Database Host</label>
                        <span class="tip">
                            You should be able to get this info from your 
                            web host, if localhost doesn't work
                        </span>
                        </div>

                    <input type="text" name="db_host" value="localhost" required>
                </div>

                <div class="form-element mb-20">
                        <div class="col">
                        <label>Database Name</label>
                        <span class="tip">
                        The name of the database you want to install NextPost in
                        </span>
                        </div>

                    <input type="text" name="db_name" value="tiktok" required>
                </div>

                <div class="form-element mb-20">
                        <div class="col">
                        <label>Username</label>
                        <span class="tip">
                        Your MySQL username
                        </span>
                        </div>

                    <input type="text" name="db_username" value="" required>
                </div>

                <div class="form-element mb-20">
                        <div class="col">
                        <label>Password</label>
                        <span class="tip">
                        Your MySQL password
                        </span>
                        </div>

                    <input type="password" name="db_password" value="">
                </div>
                
                <div class="form-element mb-20">
                        <div class="col">
                        <label>Table Prefix</label>
                        <span class="tip">
                        If you want to run multiple installation in a single database, change this
                        </span>
                        </div>

                    <input type="text" name="db_tablePrefix" value="td_">
                </div>

            </div>
        
            <div class="sub-section mb-0">

                <h3 class="section-title">
                Administrative account details:
                </h3>
                
                <div class="row">
                    <div class="form-element col">
                        <label for="fName">FirstName: </label>
                        <input type="text" name="user_fName" id="fName" required>
                    </div>
                    <div class="form-element col">                    
                    <label for="lName">LastName: </label>
                        <input type="text" name="user_lName" id="lName" required>
                    </div>
                </div>
                

                <div class="row">
                    <div class="form-element col">
                        <label for="email">Email: </label>
                        <input type="email" name="user_email" id="email" required>
                    </div>
                    <div class="form-element col">                    
                    <label for="password">Password: </label>
                        <input type="password" name="user_password" id="password" required>
                    </div>
                </div>

                </div>
        
        </div>

        <button class="center mt-40">Finish Installation</button>
    </form>

</section>