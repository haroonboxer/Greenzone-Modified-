-- Home Table View for the search
CREATE VIEW search_home_table_view AS
SELECT
    areas.id AS area_id,
    areas.province_id,
    areas.district_id,
    areas.zone_id,
    areas.shared_id,
    areas.gozar_id,
    head_of_homes.id AS head_of_home_id,
    areas.area_information,
    areas.created_at,
    areas.remark,
    head_of_homes.name AS head_of_home_name,
    head_of_homes.serial_number,
    head_of_homes.father_name,
    head_of_homes.identity_card_number,
    ethnicities.name AS ethnicities_name,
    languages.name AS language_name,
    religions.religion_name AS religion_name,
    zones.name AS zone,
    provinces.name_dr AS province,
    districts.name_dr AS district,
    shareds.name AS shared_name,
    gozars.name AS gozar_name,
    home_family_members.head_of_family_id,
    home_family_members.family_member_serial_number,
    home_family_members.family_member_id,
    home_family_members.family_member_name,
    home_family_members.family_member_father_name,
    home_family_members.family_member_gender,
    home_family_members.family_member_marital_status,
    home_family_members.relation_with_family_head,
    home_family_members.family_id_number
FROM
    areas
    JOIN provinces ON provinces.id = areas.province_id
    JOIN districts ON districts.id = areas.district_id
    JOIN zones ON zones.id = areas.zone_id
    JOIN shareds ON shareds.id = areas.shared_id
    JOIN gozars ON gozars.id = areas.gozar_id
    JOIN head_of_homes ON head_of_homes.area_id = areas.id
    LEFT JOIN (
        SELECT
            serial_number AS family_member_serial_number,
            id as family_member_id,
            name AS family_member_name,
            father_name AS family_member_father_name,
            gender AS family_member_gender,
            marital_status AS family_member_marital_status,
            relation_with_family_head AS relation_with_family_head,
            identity_card_number AS family_id_number,
            head_of_family_id
        FROM
            home_family_members
        GROUP BY
            head_of_family_id,
            family_member_serial_number,
            family_member_id,
            family_member_name,
            family_member_father_name,
            family_member_gender,
            family_member_marital_status,
            relation_with_family_head,
            family_id_number
    ) AS home_family_members ON home_family_members.head_of_family_id = head_of_homes.id
    LEFT JOIN ethnicities ON ethnicities.id = head_of_homes.ethnicity
    LEFT JOIN languages ON languages.id = head_of_homes.language
    LEFT JOIN religions ON religions.id = head_of_homes.religion
GROUP BY
    head_of_homes.id,
    areas.id,
    areas.province_id,
    areas.district_id,
    areas.zone_id,
    areas.shared_id,
    areas.gozar_id,
    areas.area_information,
    areas.created_at,
    areas.remark,
    head_of_homes.name,
    head_of_homes.serial_number,
    head_of_homes.father_name,
    head_of_homes.identity_card_number,
    home_family_members.head_of_family_id,
    home_family_members.family_member_serial_number,
    home_family_members.family_member_id,
    home_family_members.family_member_name,
    home_family_members.family_member_father_name,
    home_family_members.family_member_gender,
    home_family_members.family_member_marital_status,
    home_family_members.relation_with_family_head,
    home_family_members.family_id_number,
    family_id_number,
    ethnicities_name,
    language_name,
    religion_name,
    zones.name,
    province,
    district,
    shared_name,
    gozar_name;
--End of the home table view.





-- Head of home view
CREATE VIEW head_of_home_view AS
SELECT
    head_of_homes.id,
    head_of_homes.home_id,
    homes.area_id,
	homes.created_at,
    head_of_homes.serial_number,
    head_of_homes.name,
    head_of_homes.nick_name,
    head_of_homes.father_name,
    head_of_homes.grand_father_name,
    head_of_homes.identity_card_number,
    head_of_homes.main_location_province,
    head_of_homes.main_location_district,
    head_of_homes.main_location_village,
    head_of_homes.birth_date,
    head_of_homes.birth_place,
    head_of_homes.ethnicity,
    head_of_homes.religion,
	head_of_homes.sect,
    head_of_homes.electricity_meter_number,
    head_of_homes.number_of_members_male,
    head_of_homes.number_of_members_female,
    head_of_homes.existince_in_political_party,
    head_of_homes.phone_number1,
    head_of_homes.phone_number2,
    head_of_homes.current_job,
    head_of_homes.current_job_place,
    head_of_homes.current_job_date,
    head_of_homes.previous_job_place,
    head_of_homes.education_degree,
    head_of_homes.education_refrence,
    head_of_homes.graduation_date,
    head_of_homes.type_of_house,
    head_of_homes.start_date_of_contraction,
    head_of_homes.end_date_of_contraction,
    head_of_homes.photo,
    head_of_homes.sherid_employee,
    head_of_homes.matter_of_residantial_control,
    head_of_homes.criminal_manager,
    homes.main_lane_number,
    homes.sub_lane_number,
    homes.home_type,
    homes.apartment_type,
    homes.apartment_number,
    homes.block_number,
    homes.zina_and_manzel_number,
    homes.home_number,
    homes.gps_longitude,
    homes.gps_latitude,
    homes.track_point,
    homes.remark,
    religions.religion_name AS religionName,
    ethnicities.name AS ethnicityName,
    sects.name AS sectName,
    provinces.name_dr AS province,
    districts.district_dr AS district,
    users.name AS ownerName,
    departments.name_da AS departmentName,
    loc.name_dr AS createdLocationName
FROM
    head_of_homes
JOIN provinces ON provinces.id = head_of_homes.main_location_province
JOIN districts ON districts.id = head_of_homes.main_location_district
JOIN departments ON departments.id = head_of_homes.created_department
JOIN provinces AS loc ON loc.id = head_of_homes.created_location
JOIN users ON users.id = head_of_homes.created_by
JOIN religions ON religions.id = head_of_homes.religion
JOIN ethnicities ON ethnicities.id = head_of_homes.ethnicity
JOIN sects ON sects.id = head_of_homes.sect
JOIN homes ON homes.id = head_of_homes.home_id;
-- End of head of home view.